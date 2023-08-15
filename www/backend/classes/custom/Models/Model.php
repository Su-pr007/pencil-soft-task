<?php

namespace Custom\Models;

use \Custom\Database\Mysql;
use \Custom\Exceptions\Mysql\ElementNotFoundException;
use Custom\Exceptions\Mysql\ExecuteQueryException;

abstract class Model
{
    protected const TABLE_NAME = '';
    protected const ID_COLUMN_NAME = 'id';
    protected const GET_ALL_STRING = 'SELECT * FROM %s;';
    protected const GET_BY_ID_STRING = 'SELECT * FROM %s WHERE %s = :id LIMIT 1;';
    protected const DELETE_STRING = 'DELETE FROM %s WHERE `id`=:id;';

    // Костыль для такого проекта
    protected const INSERT_STRING = '';
    protected const UPDATE_STRING = '';
    
    public static function getFullList(): array
    {
        return Mysql::executeQuery(
            sprintf(static::GET_ALL_STRING, static::TABLE_NAME)
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(string $id): array
    {
        $foundElement = Mysql::executeQuery(
            sprintf(
                static::GET_BY_ID_STRING,
                static::TABLE_NAME,
                static::ID_COLUMN_NAME
            ),
            [
                'id' => $id
            ])->fetch(\PDO::FETCH_ASSOC);
        if (empty($foundElement)) {
            throw new ElementNotFoundException($id);
        }

        return $foundElement;
    }

    public static function create(array $parameters): void
    {
        try {
            Mysql::executeQuery(
                sprintf(static::INSERT_STRING, static::TABLE_NAME),
                $parameters
            );
        } catch(\PDOException) {
            throw new ExecuteQueryException;
        }
    }

    public static function change(string $id, array $parameters): void
    {
        try {
            Mysql::executeQuery(
                sprintf(static::UPDATE_STRING, static::TABLE_NAME),
                [
                    ...$parameters,
                    'id' => $id
                ]
            );
        } catch(\PDOException) {
            throw new ExecuteQueryException;
        }
    }

    public static function delete(string $id): void
    {
        Mysql::executeQuery(
            sprintf(
                static::DELETE_STRING,
                static::TABLE_NAME
            ),
            [
                'id' => $id
            ]
        );
    }
}