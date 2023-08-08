<?php

namespace Custom\Database;

use \Custom\Database\Config;

class Mysql
{
	public function connectToDatabase()
	{
		return mysqli_connect(Config::getDBHost(), Config::getDBUser(), Config::getDBPassword(), Config::getDBName());
	}

	public function executeQuery(string $query)
	{
		
	}
}