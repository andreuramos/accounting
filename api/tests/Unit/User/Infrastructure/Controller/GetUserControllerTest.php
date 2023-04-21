<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\UseCase\GetUserUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use App\User\Infrastructure\Controller\GetUserController;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetUserControllerTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;
    private $getUserUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->getUserUseCase = $this->prophesize(GetUserUseCase::class);
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
        $email = new Email("some@email.com");
        $user = new User(new Id(1), $email,"");
        $this->userRepository->getByEmail($email)
            ->willReturn($user);
        $this->getUserUseCase->__invoke($email)->willReturn([
            'email' => "some@email.com"
        ]);
        $controller = $this->buildController();

        $result = $controller($request);

        $this->assertEquals("application/json", $result->headers->get('Content-Type'));
        $this->assertJson($result->getContent());
        $decodedResult = json_decode($result->getContent(), true);
        $this->assertArrayHasKey('email', $decodedResult);
    }

    /**
     * @return GetUserController
     */
    private function buildController(): GetUserController
    {
        return new GetUserController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->getUserUseCase->reveal()
        );
    }
}
