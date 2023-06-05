<?php

namespace App\Tax\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\Tax\Application\Command\SetUserTaxDataCommand;
use App\Tax\Application\UseCase\SetUserTaxDataUseCase;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class SetUserTaxDataController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly SetUserTaxDataUseCase $setTaxDataUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    private const MANDATORY_PARAMETERS = [
        'tax_name',
        'tax_number',
        'tax_address_street',
        'tax_address_zip_code',
    ];

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $content = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($content);

        $command = new SetUserTaxDataCommand(
            $this->authUser,
            $content['tax_name'],
            $content['tax_number'],
            $content['tax_address_street'],
            $content['tax_address_zip_code'],
        );
        ($this->setTaxDataUseCase)($command);

        return new ApiResponse([]);
    }

    private function guardMandatoryParameters(array $content): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (!array_key_exists($parameter, $content)) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
