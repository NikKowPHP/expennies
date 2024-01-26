<?php

declare(strict_types=1);
namespace App\Middleware;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationErrosMiddleware implements MiddlewareInterface
{
	public function __construct(private readonly Twig $twig)
	{

	}
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if (!empty($_SESSION['errors'])) {
			$errors = $_SESSION['errors'];
			$this->twig->getEnvironment()->addGlobal('errors', $errors);
			unset($errors);
		}
		return $handler->handle($request);
	}

}