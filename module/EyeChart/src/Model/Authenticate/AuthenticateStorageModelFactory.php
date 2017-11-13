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
        $authenticateStorageDAO = $container->get(AuthenticateStorageDAO::class);
        $authenticateEntity     = $container->get(AuthenticateEntity::class);
        $config                 = new Config($container->get('Config'));
        $environments           = $config->get('environments');

        return new AuthenticateStorageModel(
            $authenticateStorageDAO,
            $authenticateEntity,
            $environments
        );
    }
}
