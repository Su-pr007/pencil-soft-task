<?php

namespace Custom\Services;

use Custom\Exceptions\Mysql\ElementNotFoundException;
use Custom\ORM\ExpenseModel;

class ExpenseService
{
    public static function getList(): array
    {
        return ExpenseModel::getFullList();
    }

    public static function getById(int|string $id): array
    {
        return ExpenseModel::getById($id);
    }

    public static function createItem(array $parameters): void
    {
        ExpenseModel::create($parameters);
    }

    public static function changeItem(int|string $id, array $parameters): void
    {
        if (!self::isElementExists($id)) {
            throw new ElementNotFoundException('Не найдена позиция с заданным id');
        }
        ExpenseModel::change($id, $parameters);
    }

    public static function delete(string $id): void
    {
        if (!self::isElementExists($id)) {
            throw new ElementNotFoundException('Не найдена позиция с заданным id');
        }
        ExpenseModel::delete($id);
    }

    public static function isElementExists(int|string $id): bool
    {
        try {
            ExpenseModel::getById($id);

            return true;
        } catch (ElementNotFoundException) {
            return false;
        }
    }
}