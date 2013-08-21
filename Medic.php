<?

class Medic extends Unit {
	
	public $base_atk = 3;
	public $str = 3;

	public function gain_experience($target = null) {
		$gained = Dice::roll('1d4') * $this->level;
		$this->exp += $gained;
	}

	public function take_action($unit) {
		$roll = Dice::roll('1d8');

		$heal_amt = $this->base_atk + $roll;
		$target_def = $unit->ac + $unit->def;

		if ($roll === 1) {
			Report::miss_heal($this, $unit);
		} else if ($roll == 8 || ($heal_amt > $target_def)) {
			$this->heal($unit, $heal_amt);
			Report::heal($this, $unit, $heal_amt);
		}
	}

	private function heal($unit, $amt) {
		$unit->hitpoints = min($unit->hitpoints + $amt, $unit->max_hitpoints);
	}
}