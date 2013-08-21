<?

class Medic extends Soldier {
	
	public $str = 3;

	public function attack($soldier) {
		$roll 		= Dice::roll('1d8');

		$heal_amt = $roll + $this->base_atk + $this->str;

		$enemy_def 	= $soldier->ac + $soldier->def;
		if ($heal_amt == 0) {
			Report::miss_heal($this, $soldier);
		} else if ($roll == 8 || ($heal_amt > $enemy_def)) {
			$this->heal($soldier, $heal_amt);
			Report::heal($this, $soldier, $heal_amt);
		}
	}

	public function heal($soldier, $amt) {
		$soldier->hitpoints = min($soldier->hitpoints + $amt, $soldier->max_hitpoints);
	}
}