<?php
declare(strict_types=1);

/**
 * Created by Apigility.
 * Date: 12/10/2017
 * (c) 2017
 */
namespace API\V1\Rpc\PurgeSessions;

use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Zend\Config\Config;

/**
 * Class PurgeSessionsControllerFactory
 * @package API\V1\Rpc\PurgeSessions
 */
final class PurgeSessionsControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return PurgeSessionsController
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): PurgeSessionsController
    {
        $commandBus = $container->get(CommandBus::class);
        $config     = new Config($container->get('config'));

        return new PurgeSessionsController($commandBus, $config->get('sessionConfig'));
    }
}
