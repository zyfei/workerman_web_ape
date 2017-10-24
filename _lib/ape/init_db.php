<?php
use ape\db\MySQL;
use GlobalData\Client;

global $config;
global $database;

global $cache;
if ($config["cache"] === true) {
	$cache = new Client( $config["cache_ips"] );
}

global $db;
$db = new MySQL( $database ['host'], $database ['port'], $database ['username'], $database ['password'], $database ['db_name'] );
if ($database ["cache"] === true) {
	$db->cache = $cache;
}