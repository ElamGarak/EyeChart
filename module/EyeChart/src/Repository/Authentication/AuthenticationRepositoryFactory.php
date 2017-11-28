<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Repository\Authentication;

use EyeChart\Model\Authenticate\AuthenticateModel;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\Model\Employee\EmployeeModel;
use EyeChart\Service\Authenticate\AuthenticateAdapter;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class AuthenticationRepositoryFactory
 * @package EyeChart\Repository\Authentication
 * @codeCoverageIgnore
 */
final class AuthenticationRepositoryFactory
{

    /**
     * Create and return AuthenticationRepository
     *
     * @param ContainerInterface $container
     * @return AuthenticationRepository
     */
    public function __invoke(ContainerInterface $container): AuthenticationRepository
    {
        return new AuthenticationRepository(
            $container->get(AuthenticateModel::class),
            $container->get(AuthenticateStorageModel::class),
            $container->get(AuthenticateAdapter::class),
            $container->get(EmployeeModel::class),
            new AuthenticationService()
        );
    }
}
