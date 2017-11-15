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

/**
 * Class AuthenticateStorageServiceFactory
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateStorageServiceFactory
{
    /**
     * @param ContainerInterface $controllers
     * @return AuthenticateStorageService
     */
    public function __invoke(ContainerInterface $controllers): AuthenticateStorageService
    {
        $authenticationRepository = $controllers->get(AuthenticationRepository::class);

        return new AuthenticateStorageService($authenticationRepository);
    }
}
