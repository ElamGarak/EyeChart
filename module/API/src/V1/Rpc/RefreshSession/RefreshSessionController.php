<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */

namespace API\V1\Rpc\RefreshSession;

use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Command\Commands\SessionRefreshCommand;
use EyeChart\VO\Authentication\AuthenticationVO;
use League\Tactician\CommandBus;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class RefreshSessionController
 * @package API\V1\Rpc\RefreshSession
 */
class RefreshSessionController extends AbstractActionController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var AuthenticationVO */
    private $authenticationVO;

    /** @var \stdClass */
    private $inputData;

    /** @var array */
    private $jsonReturn = [];

    /**
     * RefreshSessionController constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function refreshSessionAction()
    {
        try {
            $this->authenticate();
            $this->extractAPIData();
            $this->prepareCommandData();
            $this->executeCommand();
            $this->prepareReturnData();
        } catch (\Exception $exception) {
            return new ApiProblemResponse(
                new ApiProblem(
                    $exception->getCode(),
                    'error',
                    null,
                    $exception->getMessage()
                )
            );
        }

        return new ViewModel($this->jsonReturn);
    }

    private function authenticate(): void
    {
        $this->commandBus->handle(
            new AuthenticateCommand(
                $this->getEvent()
            )
        );
    }

    private function extractAPIData(): void
    {
        $this->inputData = json_decode($this->getRequest()->getContent());
    }

    /**
     * @codeCoverageIgnore
     */
    private function prepareCommandData(): void
    {
        $this->authenticationVO = AuthenticationVO::build()->setToken($this->inputData->token);
    }

    private function executeCommand(): void
    {
        $this->commandBus->handle(
            new SessionRefreshCommand($this->authenticationVO)
        );
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = [
            'success' => true
        ];
    }
}
