<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Infrastructure\Controller\CreateExpenseController;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseControllerTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->user = new User(new Id(1), new Email("any@email.com"), "pass");
    }

    public function test_unauthorized_request_fails()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_missing_amount_fails()
    {
        $request = $this->buildRequest([
            'description' => "ass",
            'date' => "2023-04-25",
        ], ['Authorization' => 'Bearer my.jwt.token']);

        $this->tokenDecoder->__invoke('my.jwt.token')->willReturn([
            'user' => $this->user->email()->toString(),
            'expiration' => date_create()->getTimestamp() + 1000,
        ]);
        $this->userRepository->getByEmail($this->user->email())->willReturn($this->user);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);

    }

    private function getController(): CreateExpenseController
    {
        return new CreateExpenseController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }

    private function buildRequest(array $content, array $headers): Request
    {
        $request = new Request(
            [], [], [], [], [], [], json_encode($content)
        );
        foreach ($headers as $header => $value) {
            $request->headers->set($header, $value);
        }
        return $request;
    }
}
