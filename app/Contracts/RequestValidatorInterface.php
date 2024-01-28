<?php
declare(strict_types=1);
namespace App\Contracts;
use App\DataObjects\RegisterUserData;

interface	RequestValidatorInterface
{
	public function validate(array $data): array;
	

}