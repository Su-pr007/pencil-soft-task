<?php

namespace Custom\Models;

class ExpenseModel extends Model
{
    protected const TABLE_NAME = 'expense';

    // Сделано для сокращения времени. На нормальном проекте этого бы не было,
    // а использовался бы какой-нибудь генератор строк запросов.
    protected const INSERT_STRING = 'INSERT INTO %s (`sum`, `comment`, `date`) VALUES (:sum, :comment, :date);';
    protected const UPDATE_STRING = 'UPDATE `%s` SET `sum`=:sum, `comment`=:comment, `date`=:date WHERE `'
        . self::ID_COLUMN_NAME
        . '`=:id;';

    
}