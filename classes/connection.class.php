<?php
include("config.class.php");

class Connection {
	static $db;

	public static function getInstance() {
		try {
			self::$db = new PDO('mysql:host='.Config::read("db.hostname").';dbname='.Config::read("db.name"), Config::read("db.username"), Config::read("db.password"));
		}
		catch(PDOException $e) {
			echo $e.getMessage();
			die();
		}
		return self::$db;
	}

};


?>
