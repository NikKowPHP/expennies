<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Receipt;
use App\Entity\Category;
use App\Traits\HasTimestamps;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[Entity, Table('transactions')]
#[HasLifecycleCallbacks]
class Transaction
{
	use HasTimestamps;

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column(name: 'was_reviewed', options: ['default' => 0])]
	private bool $wasReviewed;

	#[Column]
	private string $description;

	#[Column(name: 'amount', type: Types::DECIMAL, precision: 13, scale: 3)]
	private float $amount;

	#[Column]
	private \DateTime $date;

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
	public function wasReviewed(): bool
	{
		return $this->wasReviewed;
	}
	public function setReviewed(bool $wasReviewed): Transaction
	{
		$this->wasReviewed = $wasReviewed;
		return $this;
	}

}