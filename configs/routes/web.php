<?php

declare(strict_types=1);

use App\Controllers\ImportTransactionsController;
use App\Controllers\ReceiptController;
use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middleware\GuestMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\CategoryController;
use App\Controllers\TransactionController;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);
    $app->post('/logout', [AuthController::class, 'logOut'])->add(AuthMiddleware::class);

    $app->group('', function (RouteCollectorProxy $guest) {
        $guest->get('/login', [AuthController::class, 'loginView']);
        $guest->get('/register', [AuthController::class, 'registerView']);
        $guest->post('/login', [AuthController::class, 'logIn']);
        $guest->post('/register', [AuthController::class, 'register']);
    })->add(GuestMiddleware::class);

    $app->group('/categories', function (RouteCollectorProxy $categories) {
        $categories->get('', [CategoryController::class, 'index']);
        $categories->get('/load', [CategoryController::class, 'load']);
        $categories->post('', [CategoryController::class, 'store']);
        $categories->delete('/{id:[0-9]+}', [CategoryController::class, 'delete']);
        $categories->get('/{id:[0-9]+}', [CategoryController::class, 'get']);
        $categories->post('/{id:[0-9]+}', [CategoryController::class, 'update']);
    })->add(AuthMiddleware::class);
    $app->group('/transactions', function (RouteCollectorProxy $transactions) {
        $transactions->get('', [TransactionController::class, 'index']);
        $transactions->post('', [TransactionController::class, 'store']);
        $transactions->get('/load', [TransactionController::class, 'load']);
        $transactions->post('/import', [ImportTransactionsController::class, 'import']);
        $transactions->delete('/{id:[0-9]+}', [TransactionController::class, 'delete']);
        $transactions->get('/{transaction}', [TransactionController::class, 'get']);
        $transactions->post('/{transaction}', [TransactionController::class, 'update']);
        $transactions->post('/{id:[0-9]+}/receipts', [ReceiptController::class, 'store']);
        $transactions->get('/{transactionId:[0-9]+}/receipts/{id:[0-9]+}', [ReceiptController::class, 'download']);
        $transactions->delete('/{transactionId:[0-9]+}/receipts/{id:[0-9]+}', [ReceiptController::class, 'delete']);
        $transactions->post('/{transaction}/review', [TransactionController::class, 'toggleReviewed']);

    })->add(AuthMiddleware::class);
};
