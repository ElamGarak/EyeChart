<?php
declare(strict_types=1);

/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */

namespace API\V1\Rpc\RefreshSession;

use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;

/**
 * Class RefreshSessionControllerFactory
 * @package API\V1\Rpc\RefreshSession
 */
final class RefreshSessionControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RefreshSessionController
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): RefreshSessionController
    {
        $commandBus = $container->get(CommandBus::class);

        return new RefreshSessionController($commandBus);
    }
}
