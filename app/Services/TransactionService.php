<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Entity\Transaction;
use App\DataObjects\TransactionData;
use App\DataObjects\DataTableQueryParams;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Contracts\EntityManagerServiceInterface;

class TransactionService 
{

	   public function __construct(private readonly EntityManagerServiceInterface $entityManager)
    {
    }

	public function create(TransactionData $transactionData, User $user): Transaction
	{
		$transaction = new Transaction();

		$transaction->setUser($user);

		return $this->update($transaction, $transactionData);
	}

	public function getPaginatedTransactions(DataTableQueryParams $params): Paginator
	{
		$query = $this->entityManager
			->getRepository(Transaction::class)
			->createQueryBuilder('t')
			->select('t', 'c', 'r')
			->leftJoin('t.category', 'c')
			->leftJoin('t.receipts', 'r')
			->setFirstResult($params->start)
			->setMaxResults($params->length);

		$orderBy = in_array($params->orderBy, ['description', 'amount', 'date'])
			? $params->orderBy
			: 'date';
		$orderDir = strtolower($params->orderDir) === 'asc' ? 'asc' : 'desc';

		if (!empty($params->searchTerm)) {
			$query->where('t.description LIKE :description')
				->setParameter('description', '%' . addcslashes($params->searchTerm, '%_') . '%');
		}

		$query->orderBy('t.' . $orderBy, $orderDir);

		return new Paginator($query);
	}


	public function getById(int $id): ?Transaction
	{
		return $this->entityManager->find(Transaction::class, $id);
	}

	public function update(Transaction $transaction, TransactionData $transactionData): Transaction
	{
		$transaction->setDescription($transactionData->description);
		$transaction->setAmount($transactionData->amount);
		$transaction->setDate($transactionData->date);
		$transaction->setCategory($transactionData->category);


		return $transaction;
	}
	public function toggleReviewed(Transaction $transaction): void
	{
		$transaction->setReviewed(!$transaction->wasReviewed());
	}
}