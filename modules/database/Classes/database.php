<?php

namespace Mooo\Modules\Database\Classes;

/**
 * This class handles database-PHP interactions, which can be helpful for the
 * query builder
 * 
 * @see DB class
 * @author khernik
 */
class Database {
	
	/**
	 * @var Object Pdo connection handler
	 */
	public static $_pdo;
	
	/**
	 * Connect to the database with values loaded from the configuration file
	 * (in APATH by default)
	 */
	public static function connect()
	{
		$config = new \Mooo\System\ConfigReader\ConfigReader('database');
			$host = $config->load('host');
			$db   = $config->load('db');
			$user = $config->load('user');
			$psw  = $config->load('psw');
		
		// Create connection
		Database::$_pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $psw);
		
		// Secure connection
		Database::secureConnection();
	}
	
	/**
	 * Solves problems with the possibility of SQL injection's use
	 */
	private static function secureConnection()
	{
		Database::$_pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		Database::$_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	// Execute the query - created by query builder in DB class
	public static function execute($query, $variables)
	{
		Database::connect();
			
		// Results of given query
		$results = [];		
		$results = Database::$_pdo->prepare((string) $query);
		$results->execute($variables);
		
		return $results;
	}
	
} // End \Mooo\Modules\Database\Classes\Database
