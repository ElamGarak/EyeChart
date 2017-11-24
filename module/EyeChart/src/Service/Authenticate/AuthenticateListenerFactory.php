<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\Entity\AuthenticateEntity;
use Psr\Container\ContainerInterface;
use Zend\Config\Config;

/**
 * Class AuthenticateListenerFactory
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateListenerFactory
{

    /**
     * Create and return AuthenticateListener
     *
     * @param ContainerInterface $container
     * @return AuthenticateListener
     */
    public function __invoke(ContainerInterface $container): AuthenticateListener
    {
        return new AuthenticateListener(
            $container->get(AuthenticateService::class),
            $container->get(AuthenticateEntity::class),
            new Config($container->get('config'))
        );
    }
}
