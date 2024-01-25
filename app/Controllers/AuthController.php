<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class AuthController
{
	public function __construct(private readonly Twig $twig)
	{

	}
	public function loginView(Request $request, Response $response):Response
	{
		return $this->twig->render($response, 'auth/login.twig');
	}
	public function registerView(Request $request, Response $response):Response
	{
		return $this->twig->render($response, 'auth/register.twig');
	}

	public function register(Request $request, Response $response): Response
	{
		return $response;
	}
}