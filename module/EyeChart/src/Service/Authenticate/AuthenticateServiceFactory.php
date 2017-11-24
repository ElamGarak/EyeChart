<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\Repository\Authentication\AuthenticationRepository;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService as ZendAuthentication;

/**
 * Class AuthenticateServiceFactory
 * @package EyeChart\Service\Authenticate
 */
class AuthenticateServiceFactory
{

    /**
     * Create and return AuthenticateService
     *
     * @param ContainerInterface $container
     * @return \EyeChart\Service\Authenticate\AuthenticateService
     */
    public function __invoke(ContainerInterface $container): AuthenticateService
    {
        return new AuthenticateService(
            $container->get(AuthenticationRepository::class),
            new ZendAuthentication()
        );
    }
}
