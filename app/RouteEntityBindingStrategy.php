<?php

declare(strict_types = 1);

namespace App;

use App\Contracts\EntityManagerServiceInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use Slim\Interfaces\InvocationStrategyInterface;

class RouteEntityBindingStrategy implements InvocationStrategyInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ): ResponseInterface
		{


			return $callable($request, $response, $routeArguments);

		}
	}