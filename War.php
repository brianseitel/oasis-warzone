<?

class War {
	
	const DELAY = 100000;

	const ARMIES = 2;
	const NUMBER_OF_UNITS = 20;
	const EXP_PER_KILL = 100;

	public static $armies = array();

	public static function begin_battle() {
		// Pick two armies and fight
		shuffle(self::$armies);
		
		// Fight between 2 and 5 rounds
		$rounds = 1 + Dice::roll('1d4');

		$army1 = self::$armies[0];
		$army2 = self::$armies[1];
		Battle::engage($army1, $army2, $rounds);

		War::cleanup();
	}

	public static function declare_war($armies) {
		self::$armies = $armies;
	}

	public static function end_battle() {
		foreach (self::$armies as $army)
			$army->promote()->recover();
	}

	public static function rank_armies() {
		$armies = self::$armies;
		usort($armies, function($a, $b) { return $a->total_kills > $b->total_kills; });
		return $armies;
	}

	public static function start() {
		$armies = array();

		for ($i = 0; $i < self::ARMIES; $i++) {
			$army = new Army;
			$army->draft(self::NUMBER_OF_UNITS);
			$armies[] = $army;
		}

		Report::start($armies);

		War::declare_war($armies);

		while (War::still_fighting()) {
			War::begin_battle();
			War::end_battle();
		}

		Report::end(array_shift(War::$armies));
	}

	public static function still_fighting() {
		return count(self::$armies) > 1;
	}

	private static function cleanup() {
		$count = count(self::$armies);
		self::$armies = array_filter(self::$armies, function ($army) { 
			
			$has_units = count($army->units) > 0;

			$medics = array_filter($army->units, function($unit) {
				return Util::type($unit, 'Medic');
			});

			$only_medics = count($army->units) === count($medics);


			return $has_units && !$only_medics;
		});
	}
}