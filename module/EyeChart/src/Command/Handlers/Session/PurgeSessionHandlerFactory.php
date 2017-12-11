<?php

/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/10/2017
 * (c) 2017
 */

namespace EyeChart\Command\Handlers\Session;

use Psr\Container\ContainerInterface;

/**
 * Class PurgeSessionHandlerFactory
 * @package EyeChart\Command\Handlers\Session
 */
final class PurgeSessionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return SessionRefreshHandler
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): PurgeSessionHandler
    {
        return new PurgeSessionHandler(
            $container->get(PurgeSessionHandler::class)
        );
    }
}
