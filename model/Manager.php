<?php

abstract class Manager{
	static protected function dbConnect()
	{
		$db = new PDO
			(''.BDD_SGBD.':host='.BDD_HOST.';dbname='.BDD_DATABASE.'',''.BDD_USER.'',''.BDD_PASSWORD.'',
				[
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				]
			); 
		$db->exec('SET NAMES UTF8');
		return $db;
	}
}