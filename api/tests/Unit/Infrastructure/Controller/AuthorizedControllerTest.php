<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Domain\Entities\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Auth\JWTDecoder;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

abstract class AuthorizedControllerTest extends BaseControllerTest
{
    use ProphecyTrait;

    protected const TOKEN = 'my.jwt.token';
    protected $tokenDecoder;
    protected $userRepository;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);

        $this->user = new User(new Id(1), new Email("any@email.com"), "pass");
        $this->user->setAccountId(new Id(2));
        $this->tokenDecoder->__invoke(self::TOKEN)->willReturn([
            'user' => $this->user->email()->toString(),
            'expiration' => date_create()->getTimestamp() + 1000,
        ]);
        $this->userRepository->getByEmail($this->user->email())->willReturn($this->user);
    }

    public function buildAuthorizedRequest(array $body): Request
    {
        $request = $this->buildRequest($body);
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        return $request;
    }
}
