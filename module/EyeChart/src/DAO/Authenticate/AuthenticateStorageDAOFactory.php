<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use EyeChart\Entity\SessionEntity;
use Psr\Container\ContainerInterface;

/**
 * Class AuthenticateStorageDAOFactory
 * @package EyeChart\DAO\Authenticate
 */
final class AuthenticateStorageDAOFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthenticateStorageDAO
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): AuthenticateStorageDAO
    {
        $adapter = $container->get('db');
        $entity  = $container->get(SessionEntity::class);

        return new AuthenticateStorageDAO($adapter, $entity);
    }
}
