<?php


use Custom\Controllers\ExpenseController;

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/api');

$app->get('/test/expense', [ExpenseController::class, 'index']);
$app->get('/test/expense/{id}', [ExpenseController::class, 'show']);
$app->post('/test/expense', [ExpenseController::class, 'store']);
$app->patch('/test/expense/{id}', [ExpenseController::class, 'update']);
$app->delete('/test/expense/{id}', [ExpenseController::class, 'destroy']);

$app->run();
