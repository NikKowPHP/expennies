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

}