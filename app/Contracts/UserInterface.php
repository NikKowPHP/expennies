<?php
declare(strict_types=1);
namespace App\Contracts;

interface UserInterface
{
	public function getId():int;
	public function getPassword():string;
	public function getName():string;
	public function getEmail():string;
	public function setVerifiedAt( ?\DateTimeInterface $verifiedAt): void;
}