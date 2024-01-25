<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
	public function __construct(private ResponseFactoryInterface $responseFactory)
	{
	}
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
	{
		try {
			return $handler->handle($request);

		} catch (ValidationException $e) {
			$response = $this->responseFactory->createResponse();

			return $response->withHeader('Location', '/register')->withStatus(302);


		}
	}

}