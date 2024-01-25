<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Receipt;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[Entity, Table('transactions')]
class Transaction
{

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column]
	private string $description;

	#[Column(name: 'amount', Type::Types::DECIMAL, precision: 13, scale: 3)]
	private float $amount;

	#[Column]
	private \DateTime $date;

	#[Column(name: 'created_at')]
	private \DateTime $createdAt;

	#[Column(name: 'updated_at')]
	private \DateTime $updatedAt;

	#[ManyToOne(inversedBy: 'transactions')]
	private User $user;

	#[ManyToOne(inversedBy: 'transactions')]
	private Category $category;

	#[OneToMany(mappedBy: 'transaction', targetEntity: Receipt::class)]
	private Collection $receipts;

	public function __construct()
	{
		$this->receipts = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function getAmount(): float
	{
		return $this->amount;
	}

	public function setAmount(float $amount): void
	{
		$this->amount = $amount;
	}

	public function getDate(): \DateTime
	{
		return $this->date;
	}

	public function setDate(\DateTime $date): void
	{
		$this->date = $date;
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

	public function setUser(User $user): Transaction 
	{
		$user->addTransaction($this);
		$this->user = $user;
		return $this;
	}

	public function getCategory(): Category
	{
		return $this->category;
	}

	public function setCategory(Category $category): Transaction
	{
		$category->addTransaction($this);
		$this->category = $category;
		return $this;
	}

	public function getReceipts(): Collection
	{
		return $this->receipts;
	}

	public function addReceipt(Receipt $receipt): Transaction
	{
		if (!$this->receipts->contains($receipt)) {
			$this->receipts->add($receipt);
			return $this;
		}
		return $this;
	}

	public function removeReceipt(Receipt $receipt): void
	{
		if ($this->receipts->removeElement($receipt)) {
			$receipt->setTransaction(null);
		}
	}

}