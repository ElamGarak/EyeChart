<?php
declare(strict_types=1);

/**
 * Created by Apigility.
 * Date: 12/10/2017
 * (c) 2017
 */
namespace API\V1\Rpc\PurgeSessions;

use EyeChart\Command\Commands\PurgeSessionCommand;
use League\Tactician\CommandBus;
use Zend\Config\Config;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ContentNegotiation\ViewModel;

/**
 * Class PurgeSessionsController
 * @package API\V1\Rpc\PurgeSessions
 */
final class PurgeSessionsController extends AbstractActionController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var Config */
    private $sessionConfig;

    /** @var mixed[] */
    private $sessionConfigurations;

    /** @var array */
    private $jsonReturn = [];

    /**
     * PurgeSessionsController constructor.
     * @param CommandBus $commandBus
     * @param Config $sessionConfig
     */
    public function __construct(CommandBus $commandBus, Config $sessionConfig)
    {
        $this->commandBus    = $commandBus;
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * @return ApiProblemResponse|ViewModel
     */
    public function purgeSessionsAction()
    {
        try {
            $this->executeCommand();
            $this->prepareCommandData();
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

    /**
     * @codeCoverageIgnore
     */
    private function prepareCommandData(): void
    {
        $this->sessionConfigurations = $this->sessionConfig->toArray();
    }

    private function executeCommand(): void
    {
        $this->commandBus->handle(
            new PurgeSessionCommand($this->sessionConfigurations)
        );
    }

    private function prepareReturnData(): void
    {
        $this->jsonReturn = [
            'success' => true
        ];
    }
}
