<?
date_default_timezone_set('America/Los_Angeles');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('ARMIES', 2);
define('NUMBER_OF_UNITS', 20); // Units per army

$start = microtime(1);

$armies = array();

for ($i = 0; $i < ARMIES; $i++) {
	$army = new Army;
	$army->draft(NUMBER_OF_UNITS);
	$armies[] = $army;
}

Report::start($armies);

War::declare_war($armies);

while (War::still_fighting()) {
	War::begin_battle();
	War::end_battle();
}

Report::end(array_shift(War::$armies));


echo "\n\n**** WAR OVER IN ".(microtime(1) - $start)." seconds ****\n\n";




function __autoload($class_name) {
	include $class_name.'.php';
}

function pd($a) {
	print_r($a);die();
}