<?php

namespace Test\Unit\Shared\Infrastructure\Controller;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class ControllerTest extends TestCase
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
        $this->tokenDecoder->__invoke(self::TOKEN)->willReturn([
            'user' => $this->user->email()->toString(),
            'expiration' => date_create()->getTimestamp() + 1000,
        ]);
        $this->userRepository->getByEmail($this->user->email())->willReturn($this->user);
    }
}
