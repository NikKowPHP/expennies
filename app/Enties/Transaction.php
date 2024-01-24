<?php 
declare(strict_types=1);
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;



#[Entity, Table('transactions')]
class Transaction
{

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column]
	private string $description;

	#[Column(name: 'amount', Type:: Types::DECIMAL, precision: 13, scale: 3)]
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

}