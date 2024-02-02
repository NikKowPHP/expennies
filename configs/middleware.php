<?php

declare(strict_types = 1);

use App\Config;
use App\Middleware\CsrfFieldsMiddleware;
use App\Middleware\OldFormMiddleware;
use App\Middleware\StartSessionsMiddleware;
use App\Middleware\ValidationErrosMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();
    $config    = $container->get(Config::class);

    $app->add(MethodOverrideMiddleware::class);
    $app->add(CsrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(ValidationErrosMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->add(OldFormMiddleware::class);
    $app->add(StartSessionsMiddleware::class);

    $app->addErrorMiddleware(
        (bool) $config->get('display_error_details'),
        (bool) $config->get('log_errors'),
        (bool) $config->get('log_error_details')
    );
};
