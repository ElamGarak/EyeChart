<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Handlers\Authenticate;

use Psr\Container\ContainerInterface;
use EyeChart\Service\Authenticate\AuthenticateListener;

/**
 * Class AuthenticateHandlerFactory
 * @package EyeChart\Command\Handlers\Authenticate
 */
class AuthenticateHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthenticateHandler
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container)
    {
        $authenticate = $container->get(AuthenticateListener::class);

        return new AuthenticateHandler($authenticate);
    }
}
