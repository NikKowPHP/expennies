<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;


#[Entity, Table('users')]
#[HasLifecycleCallbacks]
class User
{

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column]
	private string $name;

	#[Column]
	private string $email;

	#[Column]
	private string $password;

	#[Column(name: 'created_at')]
	private \DateTime $createdAt;

	#[Column(name: 'updated_at')]
	private \DateTime $updatedAt;

	#[OneToMany(mappedBy: 'user', targetEntity: Transaction::class)]
	private Collection $transactions;

	#[OneToMany(mappedBy: 'user', targetEntity: Category::class)]
	private Collection $categories;

	public function __construct()
	{
		$this->transactions = new ArrayCollection();
		$this->categories = new ArrayCollection();

	}

	public function getId(): int
	{
		return $this->id;
	}

	#[PrePersist, PreUpdate]
	public function updateTimestamps(LifecycleEventArgs $args): void
	{
		if (!isset($this->createdAt)) {
			$this->createdAt = new \DateTime();
		}
		$this->updatedAt = new \DateTime();

	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
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

	public function getTransactions(): Collection
	{
		return $this->transactions;
	}

	public function addTransaction(Transaction $transaction): User
	{
		if (!$this->transactions->contains($transaction)) {
			$this->transactions->add($transaction);
			return $this;
		}
		return $this;
	}

	public function removeTransaction(Transaction $transaction): void
	{
		if ($this->transactions->removeElement($transaction)) {
			$transaction->setUser(null);
		}
	}

	public function getCategories(): Collection
	{
		return $this->categories;
	}

	public function addCategory(Category $category): User
	{
		if (!$this->categories->contains($category)) {
			$this->categories->add($category);
			return $this;
		}
		return $this;
	}

	public function removeCategory(Category $category): User
	{
		if ($this->categories->removeElement($category)) {
			$category->setUser(null);
		}
	}

}