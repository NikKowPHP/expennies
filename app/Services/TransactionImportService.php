<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use Clockwork\Clockwork;
use Clockwork\Request\LogLevel;
use DateTime;
use App\Entity\User;
use App\Entity\Transaction;
use App\DataObjects\TransactionData;

class TransactionImportService extends EntityManagerService
{
	public function __construct(
		private readonly TransactionService $transactionService,
		private readonly CategoryService $categoryService,
		private readonly Clockwork $clockwork,
		private readonly EntityManagerServiceInterface $entityManagerService,
	) {
	}
	public function importFromFile(string $file, User $user): void
	{
		$resource = fopen($file, 'r');

		$categories = $this->categoryService->getAllKeyedByName();

		fgetcsv($resource);

		$count = 1;
		$batchSize = 250;

		$this->clockwork->log(LogLevel::DEBUG, 'Memory Usage Before: ' . memory_get_usage());
		$this->clockwork->log(LogLevel::DEBUG, 'Unit of work Before: ' . $this->entityManagerService->getUnitOfWork()->size());

		while (($row = fgetcsv($resource)) !== false) {
			[$description, $amount, $date, $category] = $row;
			$date = new DateTime($date);
			$category = $categories[strtolower($category)] ?? null;
			$amount = (float) (str_replace(['$', ','], '', $amount));

			$transactionData = new TransactionData($description, $amount, $date, $category);
			$this->transactionService->create($transactionData, $user);
			if ($count % $batchSize === 0) {
				$this->entityManagerService->sync();
				$this->entityManagerService->clear(Transaction::class);

				$count = 1;
			} else {
				$count++;
			}

		}
		if ($count > 1) {

			$this->entityManagerService->sync();
			$this->entityManagerService->clear();
		}


		fclose($resource);
	}

}