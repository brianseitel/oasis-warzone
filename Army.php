<?

class Army {
	
	public $soldiers = array();
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
			$soldier = $this->assign_job();
			$soldier->army = $this->name;
			$this->soldiers[] = $soldier;
		}

		$has_medic = false;
		foreach ($this->soldiers as $soldier)
			if ($soldier instanceOf Medic)
				$has_medic = true;

		if (!$has_medic) {
			$this->soldiers[0] = new Medic;
			$this->soldiers[0]->army = $this->name;
		}
	}

	public function promote() {
		foreach ($this->soldiers as $soldier)
			$soldier->level_up();

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

		$diff = NUMBER_OF_SOLDIERS - count($this->soldiers);

		$max = $diff + mt_rand(0, count($armies) - $rank);

		$recruits = mt_rand(0, min(0, $max));
		$this->draft($recruits);

		Report::draft($this, $recruits);
	}

	public function random_soldier() {
		shuffle($this->soldiers);
		return $this->soldiers[0];
	}

	private function assign_job() {
		$medic_chance = mt_rand(0, NUMBER_OF_SOLDIERS * 3); // 1/3n chance
		if ($medic_chance === 1)
			$soldier = new Medic;
		else
			$soldier = new Soldier;

		return $soldier;
	}

	private function generate_name() {
		$names = explode("\n", file_get_contents('data/armies.txt'));
		shuffle($names);
		$this->name = ucwords(array_shift($names)." Army");
	}
}