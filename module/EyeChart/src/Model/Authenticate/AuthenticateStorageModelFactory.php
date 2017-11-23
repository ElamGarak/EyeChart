<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\SessionEntity;
use Psr\Container\ContainerInterface;
use Zend\Config\Config;

/**
 * Class AuthenticateStorageModelFactory
 * @package EyeChart\Model\Authenticate
 */
final class AuthenticateStorageModelFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthenticateStorageModel
     */
    public function __invoke(ContainerInterface $container): AuthenticateStorageModel
    {
        $config = new Config($container->get('Config'));

        return new AuthenticateStorageModel(
            $container->get(AuthenticateStorageDAO::class),
            $container->get(AuthenticateEntity::class),
            $container->get(SessionEntity::class),
            $config->get('environment')
        );
    }
}
