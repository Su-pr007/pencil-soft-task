<?php

namespace Custom\ORM;

use \Custom\Database\Mysql;
use \Custom\Exceptions\Mysql\ElementNotFoundException;
use Custom\Exceptions\Mysql\ExecuteQueryException;
use Custom\Exceptions\Mysql\InsertElementFailedException;

abstract class Model
{
    protected const TABLE_NAME = '';
    protected const ID_COLUMN_NAME = 'id';
    
    public static function getFullList(): array
    {
        return Mysql::executeQuery(
            'SELECT * FROM :tableName;',
            [
                ':tableName' => static::TABLE_NAME
            ])->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int|string $id): array
    {
        $foundElement = Mysql::executeQuery(
            'SELECT * FROM :tableName WHERE :idColumn = :idValue LIMIT 1;',
            [
                ':tableName' => static::TABLE_NAME,
                ':idColumn' => static::ID_COLUMN_NAME,
                ':idValue' => $id
            ])->fetch(\PDO::FETCH_ASSOC);
        if (empty($foundElement)) {
            throw new ElementNotFoundException($id);
        }

        return $foundElement;
    }

    public static function create(array $parameters): void
    {
        try {
            $result = Mysql::executeQuery(
                'INSERT INTO :tableName (`date`, `sum`, `comment`) VALUES (":dateValue", ":sumValue", ":commentValue");',
                [
                    ':tableName' => static::TABLE_NAME,
                    ':dateValue' => $parameters['date'],
                    ':sumValue' => $parameters['sum'],
                    ':commentValue' => $parameters['comment']
                ]
            );
    
            if (!$result) { // Если добавление не удалось
                throw new InsertElementFailedException(json_encode($parameters));
            }
        } catch(\PDOException $error) {
            throw new ExecuteQueryException(json_encode($parameters));
        }
    }

    public static function change(int|string $id, array $parameters): void
    {
        $result = Mysql::executeQuery(
            'UPDATE :tableName SET `date`=":dateValue", `sum`=":sumValue", `comment`=":commentValue" WHERE `id`=":idValue";',
            [
                ':tableName' => static::TABLE_NAME,
                ':dateValue' => $parameters['date'],
                ':sumValue' => $parameters['sum'],
                ':commentValue' => $parameters['comment'],
                ':idValue' => $id
            ]
        );

        if (!$result) { // Если изменение не удалось
            throw new InsertElementFailedException(json_encode($id));
        }
    }

    public static function delete(int|string $id): void
    {
        $result = Mysql::executeQuery(
            'DELETE FROM :tableName WHERE `id`=":idValue";',
            [
                ':tableName' => static::TABLE_NAME,
                ':idValue' => $id
            ]
        );

        if (!$result) { // Если удаление не удалось
            throw new ElementNotFoundException($id);
        }
    }
}