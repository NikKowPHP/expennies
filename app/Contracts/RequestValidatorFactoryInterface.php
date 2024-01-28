<?php
declare(strict_types=1);
namespace App\Contracts;
use App\DataObjects\RegisterUserData;

interface	RequestValidatorFactoryInterface 
{
	public function make(string $class): RequestValidatorInterface;

}