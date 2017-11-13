<?php
declare(strict_types=1);

/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */

namespace API\V1\Rpc\RefreshSession;

use EyeChart\Service\Authenticate\AuthenticateStorageService;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;

/**
 * Class RefreshSessionControllerFactory
 * @package API\V1\Rpc\RefreshSession
 */
class RefreshSessionControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RefreshSessionController
     */
    public function __invoke(ContainerInterface $container): RefreshSessionController
    {
        $service    = $container->get(AuthenticateStorageService::class);
        $commandBus = $container->get(CommandBus::class);

        return new RefreshSessionController($service, $commandBus);
    }
}