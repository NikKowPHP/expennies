<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;



#[Entity, Table('receipts')]
class Receipt
{

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column]
	private string $filename;

	#[Column(name: 'storage_filename')]
	private string $storageFilename;


	#[Column(name: 'created_at')]
	private \DateTime $createdAt;


	#[ManyToOne(inversedBy: 'receipts')]
	private Transaction $transaction;

	public function getId(): int
	{
		return $this->id;
	}

	public function getFilename(): string
	{
		return $this->filename;
	}

	public function setFilename(string $filename): void
	{
		$this->filename = $filename;
	}
	public function getStorageFilename(): string
	{
		return $this->storageFilename;
	}

	public function setStorageFilename(string $fileName): Receipt
	{
		$this->storageFilename = $fileName;
		return $this;
	}

	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTime $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getTransaction(): Transaction
	{
		return $this->transaction;
	}

	public function setTransaction(Transaction $transaction): Receipt
	{
		$transaction->addReceipt($this);
		$this->transaction = $transaction;
		return $this;
	}

}