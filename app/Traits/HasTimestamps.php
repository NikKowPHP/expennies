<?php
declare(strict_types=1);
namespace App\Traits;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Event\LifecycleEventArgs;

trait HasTimestamps
{
	#[Column(name: 'created_at')]
	private \DateTime $createdAt;

	#[Column(name: 'updated_at')]
	private \DateTime $updatedAt;

#[PrePersist, PreUpdate]
public function updateTimestamps(LifecycleEventArgs $args): void
{
	if (!isset($this->createdAt)) {
		$this->createdAt = new \DateTime();
	}
	$this->updatedAt = new \DateTime();

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

}
