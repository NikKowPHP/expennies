<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use App\DataObjects\TransactionData;
use App\RequestValidators\ImportTransactionsRequestValidator;
use App\Services\CategoryService;
use App\Services\TransactionService;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ImportTransactionsController
{
	public function __construct(
		private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
		private readonly TransactionService $transactionService,
		private readonly CategoryService $categoryService
	) {
	}

	public function import(Request $request, Response $response): Response
	{

		$file = $this->requestValidatorFactory->make(ImportTransactionsRequestValidator::class)->validate($request->getUploadedFiles())['importFile'];

		$user = $request->getAttribute('user');
		$resource = fopen($file->getStream()->getMetadata('uri'), 'r');

		$categories = $this->categoryService->getAllKeyedByName();


		fgetcsv($resource);
		
		while (($row = fgetcsv($resource)) !== false) {
			[$description, $amount, $date, $category] = $row;
			$date = new DateTime($date);
			$category = $categories[strtolower($category)] ?? null;
			$amount = (float) (str_replace(['$', ','], '', $amount));

			$transactionData = new TransactionData($description, $amount, $date, $category);
			$this->transactionService->create($transactionData, $user);
		}
		fclose($resource);
		return $response;
	}
}