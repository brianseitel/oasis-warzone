<?

define('RED', "\033[40;31m");
define('BLUE', "\033[40;36m");
define('GREEN', "\033[40;32m");
define('YELLOW', "\033[40;33m");
define('PURPLE', "\033[40;35m");
define('WHITE', "\033[40;00m");
define('GRAY', "\033[40;37m");

class Report {
	
	public static function end($winner) {
		$army = $winner;
		$winner = strtoupper($winner);
		echo PHP_EOL.GREEN."** WAR IS OVER! ".BLUE."{$winner}".GREEN." IS VICTORIOUS! **".GRAY.PHP_EOL;
		foreach ($army->units as $unit) {
			echo "\t{$unit->name} (Level: {$unit->level}): {$unit->hitpoints}/{$unit->max_hitpoints}".PHP_EOL;
		}
	}

	public static function start($armies) {
		echo PHP_EOL.GREEN."** WORLD WAR BEGINS! **".GRAY.PHP_EOL;
		echo "COMBATANTS: ".implode(", ", $armies).PHP_EOL;
	}

	public static function death($corpse) {
		echo WHITE."\t\t\t\t\t\t\t>> {$corpse}".GRAY." has been ".RED."defeated".GRAY."!".PHP_EOL;
	}

	public static function heal($medic, $target, $amount) {
		echo WHITE."{$medic} ".YELLOW."heals ".PURPLE." {$target} ".GRAY."for ".YELLOW."{$amount} hp".GRAY."!".PHP_EOL;
	}

	public static function hit($aggressor, $defender, $amount) {
		$verbs = array('smacks', 'discombobulates', 'eviscerates', 'assaults', 'attacks', 'punches', 'kicks', 'slams', 'stabs', 'incinerates');
		shuffle($verbs);
		$verb = array_shift($verbs);
		echo WHITE."{$aggressor}".GRAY." {$verb} ".PURPLE."{$defender}".GRAY." for ".RED."{$amount}".GRAY." damage! ".YELLOW."({$defender->hitpoints}/{$defender->max_hitpoints})".PHP_EOL.GRAY;
	}

	public static function miss($aggressor, $defender) {
		echo WHITE."{$aggressor}".GRAY." swings at ".PURPLE."{$defender}".GRAY." but misses!".PHP_EOL;
	}

	public static function start_battle($army1, $army2) {
		echo PHP_EOL;
		echo BLUE.">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".GRAY.PHP_EOL;
		echo BLUE."<<< ".WHITE."{$army1}".GRAY." starts a battle with ".WHITE."{$army2}".GRAY.PHP_EOL;
		echo BLUE.">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".GRAY.PHP_EOL;
	}

	public static function end_battle($winner, $loser) {
		echo BLUE.">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".GRAY.PHP_EOL;
		echo BLUE."<<< ".WHITE."{$winner}".GRAY." won the battle against ".WHITE."{$loser}".GRAY."!".PHP_EOL;
		echo BLUE.">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>".GRAY.PHP_EOL;
	}

	public static function draft($army, $number) {
		if ($number)
			echo GRAY."<<>> ".WHITE."{$army}".GRAY." drafted ".PURPLE."{$number}".GRAY." new recruits <<>>".PHP_EOL;
	}

	public static function promote($army) {
		echo GRAY."+++ ".WHITE."{$army}".GRAY." is promoting survivors +++".PHP_EOL;
	}

	public static function levelup($unit) {
		echo YELLOW."+++ ".WHITE."{$unit}".YELLOW." leveled up to level {$unit->level}! +++".GRAY.PHP_EOL;
	}
}