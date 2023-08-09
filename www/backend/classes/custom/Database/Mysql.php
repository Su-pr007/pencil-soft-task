<?php

namespace Custom\Database;

use Custom\Exceptions\Mysql\ExecuteQueryException;
use PDO;
use PDOStatement;

class Mysql
{
	private static PDO $connectionInstance;

	private static function initConnection()
	{
		if (!isset(self::$connectionInstance)) {
			self::$connectionInstance = new PDO(self::getConnectionString(), Config::getDBUser(), Config::getDBPassword());
		}
	}

	public static function executeQuery(string $query, array $parameters = []): PDOStatement
	{
		self::initConnection();

		$state = self::$connectionInstance->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		if (!$state->execute($parameters)) {
			throw new ExecuteQueryException;
		}

		return $state;
	}

	private static function getConnectionString(): string
	{
		return sprintf("mysql:host=%s;dbname=%s", Config::getDBHost(), Config::getDBName());
	}
}