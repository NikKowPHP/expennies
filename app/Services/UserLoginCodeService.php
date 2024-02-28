<?php
declare(strict_types=1);
namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\DataObjects\RegisterUserData;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\Entity\UserLoginCode;

class  UserLoginCodeService 
{
	public function __construct(private readonly EntityManagerServiceInterface $entityManager)
	{
	}
    public function generate(User $user): UserLoginCode
    {
        $userLoginCode = new UserLoginCode();
        $code = random_int(100_000, 999_999);

        $userLoginCode->setCode($code);
        $userLoginCode->setExpiration(new \DateTime('+ 10 minutes'));

        $this->entityManager->sync($userLoginCode);
        return $userLoginCode;
    }
	
	public function getById(int $userId): ?UserInterface
	{
		return $this->entityManager->find(User::class, $userId);

	}
	public function getByCredentials(array $credentials): ?UserInterface
	{
		return $this->entityManager->getRepository(User::class)->findOneBy(
			['email' => $credentials['email']]
		);
	}

	public function createUser(RegisterUserData $data): UserInterface
	{
		$user = new User();
		$user->setName($data->name);
		$user->setEmail($data->email);
		$user->setPassword(password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 12]));

		$this->entityManager->sync($user);

		return $user;
	}
	public function verifyUser(UserInterface $user):void
	{
		$user->setVerifiedAt(new \DateTime());
	}

}