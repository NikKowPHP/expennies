<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\AuthInterface;
use App\Entity\User;
use App\Exception\ValidationException;
use Slim\Views\Twig;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class AuthController
{
	public function __construct(private readonly Twig $twig, private readonly EntityManager $entityManager, private readonly AuthInterface $auth)
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




	public function logIn(Request $request, Response $response): Response
	{
		$data = $request->getParsedBody();
		$v = new Validator($data);
		$v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
		$v->rule('email', 'email');

		// $user = $this->entityManager->getRepository(User::class)->findOneBy(
		// 	['email' => $data['email']]
		// );
		// if (!$user || !password_verify($data['password'], $user->getPassword())) {
		// 	throw new ValidationException(['password' => 'You have entered an invalid username or password']);
		// }
		// session_regenerate_id();
		// $_SESSION['user'] = $user->getId();

		if (!$this->auth->attemptLogin($data)) {
			throw new ValidationException(['password' => 'You have entered an invalid username or password']);
		}

		return $response->withHeader('Location', '/')->withStatus(302);
	}




	public function logOut(Request $request, Response $response): Response
	{

		$this->auth->logOut();

		return $response->withHeader('Location', '/')->withStatus(302);
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
				['email' => $value]
			),
			'email'
		)->message('User with the given email address already exists');

		if (!$v->validate()) {
			throw new ValidationException($v->errors());
		}

		$user = new User();
		$user->setName($data['name']);
		$user->setEmail($data['email']);
		$user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]));

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return $response->withHeader('Location', '/')->withStatus(302);
	}
}