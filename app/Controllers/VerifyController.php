<?php declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VerifyController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
    ) {}
    public function index(ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, "auth/verify.twig");
    }
    public function verify(ServerRequestInterface $request,ResponseInterface $response): ResponseInterface
    {

        return $this->twig->render($response, "auth/verify.twig");
    }
}
