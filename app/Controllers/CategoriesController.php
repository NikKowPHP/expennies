<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\Services\CategoryService;
use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\RequestValidators\CreateCategoryRequestValidator;

class CategoriesController
{
	public function __construct(private readonly Twig $twig, private readonly RequestValidatorFactoryInterface $requestValidatorFactory, private readonly CategoryService $categoryService)
	{
	}

	public function index(Request $request, Response $response): Response
	{
		return $this->twig->render(
			$response,
			'categories/index.twig',
			[
				'categories' => $this->categoryService->getAll(),
			]
		);
	}

	public function store(Request $request, Response $response): Response
	{
		$data = $this->requestValidatorFactory->make(CreateCategoryRequestValidator::class)->validate($request->getParsedBody());

		$this->categoryService->create($data['name'], $request->getAttribute('user'));

		return $response->withHeader('Location', '/categories')->withStatus(302);
	}

	public function delete(Request $request, Response $response): Response
	{
		// TODO

		return $response->withHeader('Location', '/categories')->withStatus(302);
	}
}