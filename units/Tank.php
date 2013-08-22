<?

class Tank extends Unit {
	
	public $next_level = 200;
	public $hitpoints = 150;
	public $max_hitpoints = 150;

	public $str = 10;
	public $def = 10;

	public $base_atk = 35;
	public $ac = 10;

	public function __construct() {
		$this->generate_stats();
		$this->generate_name();
	}

	public function gain_experience($target = null) {
		if (!$target) return;

		// Killed
		if (!$target->alive) {
			$gained = War::EXP_PER_KILL * $target->level;
			$this->exp += $gained;

			echo "\n\n\t\t**** {$this} GAINED {$gained} XP *** \n\n";
		}
	}
	
	public function generate_name() {
		$this->name = "Tank #".mt_rand(0, War::NUMBER_OF_UNITS * 100);
	}
	

	public function take_action(Army $enemy, Army $ally) {
		$targets = array();

		$max_targets = min($this->level + 1, count($enemy->units));

		do {
			$target = $enemy->random_unit();
			if ($target->alive)
				$targets[] = $target;
		} while (!$target->alive && count($targets) < $max_targets);

		$kills = 0;
		foreach ($targets as $target) {
			$kills += $this->assault($target);
			$enemy->units = Battle::clear_dead($enemy->units);
		}

		return $kills;
	}

	private function assault(Unit $target) {
		if (!$target->alive) return 0;
		$roll = Dice::roll('2d40');

		if ($roll >= 25) {
			$atk_damage = $this->base_atk + $this->str - $roll;

			$target_def = $target->ac + $target->def;

			if ($roll == 40 || ($atk_damage > $target_def)) {
				Report::hit($this, $target, $atk_damage);
				$target->take_damage($atk_damage);
				if (!$target->alive) {
					$this->gain_experience($target);
					return 1;
				}
			}
		}

		Report::miss($this, $target);
		return 0;
	}
}