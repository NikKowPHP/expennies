<?php
declare(strict_types=1);
namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use Valitron\Validator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\ValidationException;

class   UpdateCategoryRequestValidator implements RequestValidatorInterface
{

	public function __construct(private readonly EntityManagerInterface $entityManager)
	{

	}

	public function validate(array $data): array
	{
		$v = new Validator($data);
		$v->rule('required', ['name', 'id']);
		$v->rule('lengthMax', 'name', 50);
		$v->rule('integer', 'id');

		if (!$v->validate()) {
			throw new ValidationException($v->errors());
		}
		return $data;
	}

}