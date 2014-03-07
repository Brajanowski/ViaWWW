<?php

class Config {
	static $array;

	public static function read($name) {
		return self::$array[$name];
	}

	public static function write($name, $value) {
		self::$array[$name] = $value;
	}
};

Config::write("db.hostname", "localhost");
Config::write("db.name", "viatut");
Config::write("db.username", "root");
Config::write("db.password", "");

?>
