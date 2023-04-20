<?php

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Infrastructure\ContainerFactory;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

abstract class AuthorizedController
{
    protected ?User $authUser;

    public function __construct(
        private readonly JWTDecoder $tokenDecoder,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    protected function auth(Request $request): void
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
        return ($this->tokenDecoder)($token);
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

        $email = new Email($jwtPayload['user']);
        $user = $this->userRepository->getByEmail($email);
        if (!$user) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }
}
