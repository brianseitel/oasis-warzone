<?

class Army {
	
	public $units = array();
	public $name;
	public $total_kills = 0;

	public function __construct() {
		$this->generate_name();
	}

	public function __toString() {
		return $this->name;
	}

	public function add_kills($kills) {
		$this->total_kills += $kills;
	}

	public function draft($number) {
		for ($i = 0; $i < $number; $i++) {
			$unit = $this->assign_job();
			$unit->army = $this->name;
			$this->units[] = $unit;
		}

		$has_medic = false;
		foreach ($this->units as $unit)
			if ($unit instanceOf Medic)
				$has_medic = true;

		if (!$has_medic) {
			$this->units[0] = new Medic;
			$this->units[0]->army = $this->name;
		}
	}

	public function promote() {
		Report::promote($this);
		foreach ($this->units as $unit)
			$unit->level_up();

		return $this;
	}

	public function recover() {
		$armies = War::rank_armies();
		$rank = count($armies);
		foreach ($armies as $i => $army)
			if ($army == $this) {
				$rank = $i+1;
				break;
			}

		$diff = War::NUMBER_OF_UNITS - count($this->units);

		$diff = max(0, $diff);
		$recruits = Dice::roll("1d{$diff}");

		if ($recruits > 0) {
			$this->draft($recruits);
			Report::draft($this, $recruits);
		}
	}

	public function random_unit() {
		shuffle($this->units);
		return $this->units[0];
	}

	private function assign_job() {
		$roll = Dice::roll('2d20');
		if ($roll % 4 === 0)
			$unit = new Medic;
		elseif ($roll % 10 === 0)
			$unit = new Tank;
		else
			$unit = new Soldier;

		return $unit;
	}

	private function generate_name() {
		$names = explode("\n", file_get_contents('data/armies.txt'));
		shuffle($names);
		$this->name = ucwords(array_shift($names)." Army");
	}
}