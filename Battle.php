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

			if (Util::type($unit, 'Medic'))
				$target = $army1->random_unit($unit);
			else
				$target = $army2->random_unit($unit);

			$was_alive = false;
			if ($target->alive) $was_alive = true;

			if ($unit->alive && $target->alive)
				$unit->take_action($target);

			if (!$target->alive && $was_alive) {
				$army2->units = Battle::clear_dead($army2->units);
				$unit->gain_experience($target);
				$kills++;
			}
		}

		return $kills;
	}

	private static function clear_dead($units) {
		$units = array_filter($units, function($unit) { return $unit->alive; });

		sort($units);
		return $units;
	}

	private static function find_enemy($units) {
		shuffle($units);
		return $units[0];
	}

}