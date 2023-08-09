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
            sprintf('SELECT * FROM %s;', static::TABLE_NAME)
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int|string $id): array
    {
        $foundElement = Mysql::executeQuery(
            sprintf(
                'SELECT * FROM %s WHERE %s = :idValue LIMIT 1;',
                static::TABLE_NAME,
                static::ID_COLUMN_NAME
            ),
            [
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
                self::createInsertStringForModel(static::TABLE_NAME, array_keys($parameters)),
                self::createMapperToExecute($parameters)
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
            sprintf(
                'UPDATE %s SET `date`=":dateValue", `sum`=":sumValue", `comment`=":commentValue" WHERE `id`=":idValue";',
                static::TABLE_NAME
            ),
            [
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
            sprintf(
                'DELETE FROM %s WHERE `id`=":idValue";',
                static::TABLE_NAME
            ),
            [
                ':tableName' => static::TABLE_NAME,
                ':idValue' => $id
            ]
        );

        if (!$result) { // Если удаление не удалось
            throw new ElementNotFoundException($id);
        }
    }

    /**
     * Метод для формирования строки запроса добавления элементов в базу данных
     */
    private static function createInsertStringForModel(string $tableName, array $parametersColumns): string
    {
        $columns = implode(',', array_map(function ($element) {
            return "`$element`"; // Оборачиваем название каждого столбца в обратные кавычки
        }, $parametersColumns));

        $columnValues = implode(',', array_map(function ($element) {
            return "\":$element\""; // Оборачиваем каждое значение в двойные кавычки
        }, $parametersColumns));

        return sprintf("INSERT INTO `%s` (%s) VALUES (%s);", $tableName, $columns, $columnValues);
    }
    
    private static function createMapperToExecute(array $parameters): array
    {
        $result = [];

        foreach ($parameters as $parameterName => $parameterValue) {
            $result[":$parameterName"] = $parameterValue;
        }

        return $result;
    }
}