<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Entity\Receipt;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManager;
use App\DataObjects\TransactionData;
use App\DataObjects\DataTableQueryParams;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ReceiptService 
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function create($transaction, string $filename, string $storageFilename):Receipt 
    {
			$receipt = new Receipt();
			$receipt->setTransaction($transaction);
			$receipt->setFilename($filename);
			$receipt->setStorageFilename($storageFilename);
			$receipt->setCreatedAt(new \DateTime());

			$this->entityManager->persist($receipt);
			$this->entityManager->flush();

			return $receipt;
    }

    // public function getPaginatedTransactions(DataTableQueryParams $params): Paginator
    // {
    //     $query = $this->entityManager
    //         ->getRepository(Transaction::class)
    //         ->createQueryBuilder('t')
    //         ->setFirstResult($params->start)
    //         ->setMaxResults($params->length);

    //     $orderBy = in_array($params->orderBy, ['description', 'amount', 'date'])
    //         ? $params->orderBy
    //         : 'date';
    //     $orderDir = strtolower($params->orderDir) === 'asc' ? 'asc' : 'desc';

    //     if (!empty($params->searchTerm)) {
    //         $query->where('t.description LIKE :description')
    //             ->setParameter('description', '%' . addcslashes($params->searchTerm, '%_') . '%');
    //     }

    //     $query->orderBy('t.' . $orderBy, $orderDir);

    //     return new Paginator($query);
    // }

    // public function delete(int $id): void
    // {
    // }


    // public function update(Transaction $transaction, TransactionData $transactionData): Transaction
    // {

    //     return $transaction;
    // }
}