<?

class Medic extends Unit {
	
	public $base_atk = 3;
	public $str = 3;

	public function gain_experience() {
		$gained = Dice::roll('1d4') * $this->level;
		$this->exp += $gained;
	}

	public function take_action(Army $enemy, Army $ally) {
		$target = $ally->random_unit();
		$roll = Dice::roll('1d8');

		$heal_amt = $this->base_atk + $roll;
		$target_def = $target->ac + $target->def;

		if ($roll === 1) {
			Report::miss_heal($this, $target);
		} else if ($roll == 8 || ($heal_amt > $target_def)) {
			$this->heal($target, $heal_amt);
			Report::heal($this, $target, $heal_amt);
		}

		$this->gain_experience();

		return 0;
	}

	private function heal($unit, $amt) {
		$unit->hitpoints = min($unit->hitpoints + $amt, $unit->max_hitpoints);
	}
}