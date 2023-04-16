<?php

namespace App\User\Infrastructure\Auth;

use App\Shared\Infrastructure\ContainerFactory;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\HttpFoundation\Request;

trait ControllerAuthenticationTrait
{
    private ?User $authUser;

    public function auth(Request $request): void
    {
        $token = $this->getToken($request);
        $decodedToken = $this->decodeToken($token);
        $this->guardTokenNotExpired($decodedToken);
        $this->authUser = $this->getUser($decodedToken);
    }

    private function getToken(Request $request): string
    {
        $authHeader = $request->headers->get('authorization');
        if (null !== $authHeader && preg_match('/^Bearer\s(\S+)/', $authHeader, $token)) {
            return $token[1];
        }
        throw new InvalidCredentialsException();
    }

    private function decodeToken(string $token): array
    {
        $tokenDecoder = new JWTDecoder(env('JWT_SIGNATURE_KEY'));
        return $tokenDecoder($token);
    }

    private function guardTokenNotExpired(array $jwtPayload): void
    {
        if (!isset($jwtPayload['expiration'])) {
            throw new InvalidCredentialsException();
        }
        if ($jwtPayload['expiration'] < (new \DateTime())->getTimestamp()) {
            throw new InvalidCredentialsException();
        }
    }

    private function getUser(array $jwtPayload): User
    {
        if (!isset($jwtPayload['user'])) {
            throw new InvalidCredentialsException();
        }

        $repository = ContainerFactory::create()->get(UserRepositoryInterface::class);
        $email = new Email($jwtPayload['user']);
        $user = $repository->getByEmail($email);
        if (!$user) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }
}
