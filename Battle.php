<?

class Battle {

	public static $units;

	public static function engage($army1, $army2, $rounds) {
		Report::start_battle($army1, $army2);

		$kills = array();
		for ($i = 0; $i < $rounds; $i++) {
			$kills['army1'] = self::take_turn($army1, $army2);
			$kills['army2'] = self::take_turn($army2, $army1);
		}

		$army1->add_kills($kills['army1']);
		$army2->add_kills($kills['army2']);

		$winner 	= $kills['army1'] > $kills['army2'] ? $army1 : $army2;
		$loser 		= $winner == $army1 ? $army2 : $army1;

		Report::end_battle($winner, $loser);
	}

	private static function take_turn($army1, $army2) {
		$kills = 0;
		foreach ($army1->units as $i => $unit) {
			usleep(War::DELAY);
			if (!count($army2->units)) return;

			$kills += $unit->take_action($army2, $army1);
		}

		return $kills;
	}

	public static function clear_dead($units) {
		$units = array_filter($units, function($unit) { return $unit->alive; });

		sort($units);
		return $units;
	}

	private static function find_enemy($units) {
		shuffle($units);
		return $units[0];
	}

}