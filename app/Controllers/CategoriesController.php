<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\Category;
use Slim\Views\Twig;
use App\ResponseFormatter;
use App\Services\CategoryService;
use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\RequestValidators\CreateCategoryRequestValidator;
use App\RequestValidators\UpdateCategoryRequestValidator;

class CategoriesController
{
	public function __construct(
		private readonly Twig $twig,
		private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
		private readonly CategoryService $categoryService,
		private readonly ResponseFormatter $responseFormatter
	) {
	}

	public function index(Request $request, Response $response): Response
	{
		return $this->twig->render(
			$response,
			'categories/index.twig',
		);
	}

	public function store(Request $request, Response $response): Response
	{
		$data = $this->requestValidatorFactory->make(CreateCategoryRequestValidator::class)->validate($request->getParsedBody());

		$this->categoryService->create($data['name'], $request->getAttribute('user'));

		return $response->withHeader('Location', '/categories')->withStatus(302);
	}

	public function delete(Request $request, Response $response, array $args): Response
	{
		$this->categoryService->delete((int) $args['id']);

		return $response;
	}
	public function get(Request $request, Response $response, array $args): Response
	{
		$category = $this->categoryService->getById((int) $args['id']);
		if (!$category) {
			return $response->withStatus(404);
		}
		$data = ['id' => $category->getId(), 'name' => $category->getName()];
		$response = $response->withHeader('Content-Type', 'application/json');

		return $this->responseFormatter->asJson($response, $data);
	}

	public function update(Request $request, Response $response, array $args): Response
	{
		$data = $this->requestValidatorFactory->make(UpdateCategoryRequestValidator::class)->validate($args + $request->getParsedBody());

		$category = $this->categoryService->getById((int) $data['id']);


		if (!$category) {
			return $response->withStatus(404);
		}

		$this->categoryService->update($category, $data['name']);

		return $response;
	}
	public function load(Request $request, Response $response): Response
	{
		$params = $request->getQueryParams();

		$categories = $this->categoryService->getPaginatedCategories((int) $params['start'], (int) $params['length']);


		$transformer =
			function (Category $category) {
				return [
					'id' => $category->getId(),
					'name' => $category->getName(),
					'createdAt' => $category->getCreatedAt()->format('m/d/Y g:i A'),
					'updatedAt' => $category->getCreatedAt()->format('m/d/Y g:i A'),
				];
			};

		$totalCategories = count($categories);
		return $this->responseFormatter->asJson(
			$response,
			[
				'data' => array_map($transformer, (array) $categories->getIterator()),
				'draw' => (int) $params['draw'],
				'recordsTotal' => $totalCategories,
				'recordsFiltered' => $totalCategories,
			]
		);
	}
}