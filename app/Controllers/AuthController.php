<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use App\Exception\ValidationException;
use Slim\Views\Twig;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class AuthController
{
	public function __construct(private readonly Twig $twig, private readonly EntityManager $entityManager)
	{

	}
	public function loginView(Request $request, Response $response): Response
	{
		return $this->twig->render($response, 'auth/login.twig');
	}
	public function registerView(Request $request, Response $response): Response
	{
		return $this->twig->render($response, 'auth/register.twig');
	}

	public function register(Request $request, Response $response): Response
	{

		$data = $request->getParsedBody();
		$v = new Validator($data);
		$v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
		$v->rule('email', 'email');
		$v->rule('equals', 'confirmPassword', 'password')->label('Confirm password');

		$v->rule(
			fn($field, $value, $params, $fields) =>
			!$this->entityManager->getRepository(User::class)->count(
				['email'=>$value]
			),
			'email'
			)->message('User with the given email address already exists');

		if ($v->validate()) {
			echo "Yay! We're all good!";
		} else {
			throw new ValidationException($v->errors());

		}
		$user = new User();
		$user->setName($data['name']);
		$user->setEmail($data['email']);
		$user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]));

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return $response;
	}
}