<?php

namespace Test\Unit\Shared\Infrastructure\Controller;

use App\Domain\Id;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

abstract class AuthorizedControllerTest extends TestCase
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
        $request = new Request(
            [], [], [], [], [], [], json_encode($body, JSON_THROW_ON_ERROR)
        );
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        return $request;
    }
}
