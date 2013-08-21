<?

class Soldier {
	
	public $level = 1;
	public $next_level = 100;
	public $exp = 0;


	public $army;
	public $hitpoints 		= 30;
	public $max_hitpoints 	= 30;
	public $str 			= 3;
	public $def 			= 6;

	public $base_atk 		= 5;
	public $ac 				= 5;
	public $name;

	public $alive 			= true;

	public function __construct() {
		$this->generate_stats();
		$this->generate_name();
	}

	public function __toString() {
		return "{$this->name} ({$this->army})";
	}

	public function attack(Soldier $enemy) {
		$roll 		= Dice::roll('1d8');
		if ($roll === 1) {
			Report::miss($this, $enemy);
			return;
		}

		$atk_damage = $roll + $this->base_atk + $this->str;

		$enemy_def 	= $enemy->ac + $enemy->def;
		if ($atk_damage == 0) {
			Report::miss($this, $enemy);
		} else if ($roll == 8 || ($atk_damage > $enemy_def)) {
			$enemy->take_damage($atk_damage);
			Report::hit($this, $enemy, $atk_damage);
			if (!$enemy->alive)
				Report::death($enemy);
		}
	}

	public function gain_experience($enemy = null) {
		if (!$enemy) return;

		// Killed
		if (!$enemy->alive) {
			$gained = War::EXP_PER_KILL * $enemy->level;
			$this->exp += $gained;

			echo "\n\n\t\t\t\t\t{$this} INCREASED EXP BY {$gained} ({$this->exp}/{$this->next_level})\n\n\n";
		}
	}

	public function level_up() {
		if ($this->exp > $this->next_level) {
			// Level up
			$this->level++;
			$this->exp = 0;
			$this->next_level = ceil($this->next_level * log10($this->next_level));

			// Increase stats
			$more_hitpoints = ceil(log($this->level * $this->hitpoints));
			$more_str = ceil(log($this->str));
			$more_def = ceil(log($this->def));

			$this->hitpoints 		+= $more_hitpoints;
			$this->max_hitpoints 	+= $more_hitpoints;
			$this->str 				+= $more_str;
			$this->def 				+= $more_def;

			Report::levelup($this);
		}
	}

	public function take_damage($amount) {
		$this->hitpoints -= $amount;

		if ($this->hitpoints < 0) {
			$this->alive = false;
		}
	}

	private function generate_stats() {
		$this->str *= 1 + mt_rand(1,50) / 100;
		$this->def *= 1 + mt_rand(1,50) / 100;
		$this->hitpoints *=  1 + mt_rand(1,50) / 100;
		
		$this->str = ceil($this->str);
		$this->def = ceil($this->def);
		$this->hitpoints = ceil($this->hitpoints);
		$this->max_hitpoints = $this->hitpoints;
	}

	private function generate_name() {
		$names = explode("\n", file_get_contents('data/names.txt'));
		shuffle($names);
		$this->name = array_shift($names);
	}
}