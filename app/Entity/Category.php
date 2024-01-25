<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[Entity, Table('categories')]
class Category
{

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column]
	private string $name;

	#[Column(name: 'created_at')]
	private \DateTime $createdAt;

	#[Column(name: 'updated_at')]
	private \DateTime $updatedAt;

	#[ManyToOne(inversedBy: 'categories')]
	private User $user;

	#[OneToMany(mappedBy: 'category', targetEntity: Transaction::class)]
	private Collection $transactions;

	public function __construct()
	{
		$this->receipts = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTime $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): \DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(\DateTime $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): Category
	{
		$user->addCategory($this);
		$this->user = $user;
		return $this;
	}

	public function getTransactions(): Collection
	{
		return $this->transactions;
	}
	public function addTransaction(Transaction $transaction):Category
	{
		$this->transactions->add($transaction);
		return $this; 
	}

}