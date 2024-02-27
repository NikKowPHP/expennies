<?php
declare(strict_types=1);
namespace App\RequestValidators;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\RequestValidatorInterface;
use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ValidationException;

class TwoFactorLoginRequestValidator implements RequestValidatorInterface
{

	public function __construct(private readonly EntityManagerServiceInterface $entityManager)
	{

	}

	public function validate(array $data): array
	{
		$v = new Validator($data);
		$v->rule('required', ['email', 'code']);
		$v->rule('email', 'email');


		if (!$v->validate()) {
			throw new ValidationException($v->errors());
		}
		return $data;
	}

}