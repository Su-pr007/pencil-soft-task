<?php

namespace Custom\Controllers;

use Custom\Exceptions\Mysql\ElementNotFoundException;
use Custom\Exceptions\Mysql\ExecuteQueryException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Custom\Services\ExpenseService;

class ExpenseController
{

    public function index(Request $request, Response $response): Response
    {
        $expensesList = ExpenseService::getList();
        $response->getBody()->write(json_encode($expensesList));
    
        return $response;
    }

    public function show(Request $request, Response $response, $args): Response
    {
        try { // Пытаемся найти элемент
            $expensesList = ExpenseService::getById($args['id']); // Если нашли
            $responseText = json_encode($expensesList); // Создаём JSON строку с найденным элементом
        } catch (ElementNotFoundException) { // Если не удалось
            $response->withStatus(404);
            $responseText = self::formErrorResponse('Не найдена позиция с заданным id'); // Создаём JSON ответ об ошибке
        }

        $response->getBody()->write($responseText); // Записываем ответ

        return $response;
    }

    public function store(Request $request, Response $response, $args): Response
    {
        $requestPostData = (array)$request->getParsedBody();
        if (empty($requestPostData)) {
            $response->getBody()->write(self::formErrorResponse('Не переданы параметры для добавления элемента'));

            return $response;
        }

        try {
            ExpenseService::createItem($requestPostData);
            $response->withStatus(201);
            $responseText = self::formSuccessResponse("Позиция добавлена");
        } catch (ExecuteQueryException) {
            $response->withStatus(400);
            $responseText = self::formErrorResponse("Возникла ошибка при попытке выполнения запроса. Проверьте передаваемые поля");
        }
        
        $response->getBody()->write($responseText);
    
        return $response;
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $requestPostData = json_decode($request->getBody()->getContents());
        if (empty($requestPostData)) {
            $response->withStatus(400);
            $response->getBody()->write(self::formErrorResponse('Не переданы параметры для изменения позиции'));

            return $response;
        }


        try {
            ExpenseService::changeItem($args['id'], (array)$requestPostData);
            $responseText = self::formSuccessResponse("Изменения сохранены");
        } catch (ElementNotFoundException $error) {
            $response->withStatus(404);
            $responseText = self::formErrorResponse("Не найдена позиция с заданным id");
        } catch (ExecuteQueryException) {
            $response->withStatus(400);
            $responseText = self::formErrorResponse("Возникла ошибка при попытке выполнения запроса. Проверьте передаваемые поля");
        }
        
        $response->getBody()->write($responseText);
    
        return $response;
    }

    public function destroy(Request $request, Response $response, $args): Response
    {
        try {
            ExpenseService::delete($args['id']);
            $responseText = self::formSuccessResponse('Позиция удалена');
        } catch (ElementNotFoundException $error) {
            $response->withStatus(404);
            $responseText = self::formErrorResponse('Не найдена позиция с заданным id');
        }

        $response->getBody()->write($responseText);

        return $response;
    }

    private function formSuccessResponse(string $text): string
    {
        return json_encode([
            'success' => true,
            'notification' => [
                'title' => $text,
                'type' => 'success'
            ]
        ]);
    }

    private function formErrorResponse(string $text): string
    {
        return json_encode([
            'success' => false,
            'notification' => [
                'title' => $text,
                'type' => 'error'
            ]
        ]);
    }
}