<?

class War {
	
	const EXP_PER_KILL = 25;

	public static $armies = array();

	public static function declare_war($armies) {
		self::$armies = $armies;
	}

	public static function still_fighting() {
		return count(self::$armies) > 1;
	}

	public static function begin_battle() {
		// Pick two armies and fight
		shuffle(self::$armies);
		
		// Fight between 2 and 5 rounds
		$rounds = mt_rand(2,5);

		$army1 = self::$armies[0];
		$army2 = self::$armies[1];
		Battle::engage($army1, $army2, $rounds);

		War::cleanup();
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

	private static function cleanup() {
		$count = count(self::$armies);
		self::$armies = array_filter(self::$armies, function ($army) { return count($army->soldiers); });
	}
}