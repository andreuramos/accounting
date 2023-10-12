<?php

namespace App\Infrastructure\Controller;

use App\Domain\MissingMandatoryParameterException;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use App\UseCase\SetUserTaxData\SetUserTaxDataCommand;
use App\UseCase\SetUserTaxData\SetUserTaxDataUseCase;
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
