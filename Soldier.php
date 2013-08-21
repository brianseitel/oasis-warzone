<?

class Soldier extends Unit {
	
	public function gain_experience($target = null) {
		if (!$target) return;

		// Killed
		if (!$target->alive) {
			$gained = War::EXP_PER_KILL * $target->level;
			$this->exp += $gained;
		}
	}

	public function take_action(Unit $target) {
		$roll = Dice::roll('1d8');
		if ($roll === 1) {
			Report::miss($this, $target);
			return;
		}

		$atk_damage = $roll + $this->base_atk + $this->str;

		$target_def 	= $target->ac + $target->def;
		if ($atk_damage == 0) {
			Report::miss($this, $target);
		} else if ($roll == 8 || ($atk_damage > $target_def)) {
			$target->take_damage($atk_damage);
			Report::hit($this, $target, $atk_damage);
			if (!$target->alive)
				Report::death($target);
		}
	}
}