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
		foreach ($this->units as $unit)
			$unit->level_up();

		Report::promote($this);
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

		$diff = NUMBER_OF_UNITS - count($this->units);

		$max = $diff + mt_rand(0, count($armies) - $rank);

		$recruits = mt_rand(0, min(0, $max));
		$this->draft($recruits);

		Report::draft($this, $recruits);
	}

	public function random_unit() {
		shuffle($this->units);
		return $this->units[0];
	}

	private function assign_job() {
		$medic_chance = mt_rand(0, NUMBER_OF_UNITS * 3); // 1/3n chance
		if ($medic_chance === 1)
			$unit = new Medic;
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