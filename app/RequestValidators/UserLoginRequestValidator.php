<?php
declare(strict_types=1);
namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Exception\ValidationException;

class UserLoginRequestValidator implements RequestValidatorInterface
{

	public function __construct(private readonly EntityManager $entityManager)
	{

	}

	public function validate(array $data): array
	{

		$v = new Validator($data);
		$v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
		$v->rule('email', 'email');
		if(! $v->validate()){ 
			throw new ValidationException($v->errors());
		}

		return $data;
	}

}