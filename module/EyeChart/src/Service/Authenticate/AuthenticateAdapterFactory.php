<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\SessionEntity;
use Zend\Session\SessionManager;
use Interop\Container\ContainerInterface;

/**
 * Class AuthenticateAdapterFactory
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateAdapterFactory
{

    /**
     * Create and return AuthenticateAdapter
     *
     * @param ContainerInterface $controllers
     * @return AuthenticateAdapter
     */
    public function __invoke(ContainerInterface $controllers): AuthenticateAdapter
    {
        return new AuthenticateAdapter(
            $controllers->get(SessionManager::class),
            $controllers->get(SessionEntity::class),
            $controllers->get(AuthenticateEntity::class),
            $controllers->get(AuthenticateDAO::class),
            $controllers->get(AuthenticateStorageDAO::class)
        );
    }
}
