<?

class Battle {

	public static $soldiers;

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
		foreach ($army1->soldiers as $i => $soldier) {
			// usleep(100000);
			if (!count($army2->soldiers)) return;

			$enemy = $army2->random_soldier($soldier);
			$friendly = $army1->random_soldier($soldier);

			$was_alive = false;
			if ($enemy->alive) $was_alive = true;

			if ($soldier->alive && $enemy->alive)
				if ($soldier instanceOf Medic)
					$soldier->attack($friendly);
				else
					$soldier->attack($enemy);

			if (!$enemy->alive && $was_alive) {
				$army2->soldiers = Battle::clear_dead($army2->soldiers);
				$soldier->gain_experience($enemy);
				$kills++;
			}
		}

		return $kills;
	}

	private static function clear_dead($soldiers) {
		$soldiers = array_filter($soldiers, function($soldier) { return $soldier->alive; });

		sort($soldiers);
		return $soldiers;
	}

	private static function find_enemy($soldiers) {
		shuffle($soldiers);
		return $soldiers[0];
	}

}