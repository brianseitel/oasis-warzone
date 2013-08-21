<?
date_default_timezone_set('America/Los_Angeles');
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$start = microtime(1);

War::start();

echo "\n\n**** WAR OVER IN ".(microtime(1) - $start)." seconds ****\n\n";

function __autoload($class_name) {
	include $class_name.'.php';
}

function pd($a) {
	print_r($a);die();
}