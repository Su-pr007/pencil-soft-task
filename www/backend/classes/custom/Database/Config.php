<?php

namespace Custom\Database;

class Config
{
	private const DB_HOST = 'mysql';
	private const DB_PORT = '3306';

	private const DB_NAME = 'pencil-soft-db';
	private const DB_USER = 'pencil-soft-user';
	private const DB_PASSWORD = 'pencil-soft-password';

	public static function getDBHost(): string
	{
		return self::DB_HOST;
	}

	public static function getDBPort(): string
	{
		return self::DB_PORT;
	}

	public static function getDBName(): string
	{
		return self::DB_NAME;
	}

	public static function getDBUser(): string
	{
		return self::DB_USER;
	}

	public static function getDBPassword(): string
	{
		return self::DB_PASSWORD;
	}

}