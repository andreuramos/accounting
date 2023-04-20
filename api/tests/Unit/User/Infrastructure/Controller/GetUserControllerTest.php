<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use App\User\Infrastructure\Controller\GetUserController;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class GetUserControllerTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_throws_exception_when_no_auth_header()
    {
        $request = new Request();
        $controller = $this->buildController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_returns_json_with_email()
    {
        $request = new Request();
        $request->headers->set('authorization', "Bearer test");
        $this->tokenDecoder->__invoke(Argument::any())->willReturn([
            'user' => "some@email.com",
            'expiration' => (new \DateTime())->getTimestamp() + 60,
        ]);
        $user = new User(new Email("some@email.com"),"");
        $this->userRepository->getByEmail(new Email("some@email.com"))
            ->willReturn($user);
        $controller = $this->buildController();

        $result = $controller($request);


    }

    /**
     * @return GetUserController
     */
    private function buildController(): GetUserController
    {
        return new GetUserController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
