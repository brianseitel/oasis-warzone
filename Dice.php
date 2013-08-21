<?

class Dice {
	
	public static function roll($dice) {
		list($number, $sides) = explode("d", $dice);

		$result = 0;
		for ($i = 0; $i < $number; $i++)
			$result += mt_rand(1, $sides);

		return $result;
	}
}