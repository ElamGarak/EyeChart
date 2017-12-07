<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Handlers\Session;

use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use Psr\Container\ContainerInterface;

/**
 * Class SessionRefreshHandlerFactory
 * @package EyeChart\Command\Handlers\Session
 */
final class SessionRefreshHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return SessionRefreshHandler
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): SessionRefreshHandler
    {
        return new SessionRefreshHandler(
            $container->get(AuthenticateStorageModel::class)
        );
    }
}
