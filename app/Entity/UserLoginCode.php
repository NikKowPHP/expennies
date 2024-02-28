<?php
declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use App\Traits\HasTimestamps;
use DateTime;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;



#[Entity, Table('user_login_codes')]
#[HasLifecycleCallbacks]
class UserLoginCode
{
	use HasTimestamps;

	#[Id, Column(options: ['unsigned' => true]), GeneratedValue]
	private int $id;

	#[Column(length: 6)]
	private int $code;
    #[Column(name: 'is_active', options:['default'=> true])]
	private bool $isActive;
	#[Column]
	private DateTime $expirationDate;

	#[ManyToOne(inversedBy: 'categories')]
	private User $user;

	public function __construct()
	{
        $this->isActive = true;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getCode(): int
	{
		return $this->code;
	}

	public function setCode(int $code): void
	{
		$this->code= $code;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
		return $this;
	}
	public function setExpiration(DateTime $expiration): void
	{
		$this->expirationDate = $expiration;
	}


}