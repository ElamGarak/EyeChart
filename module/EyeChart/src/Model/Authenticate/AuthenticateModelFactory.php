<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\Entity\AuthenticateEntity;
use Psr\Container\ContainerInterface;

/**
 * Class AuthenticateModelFactory
 * @package EyeChart\Model\Authenticate
 */
final class AuthenticateModelFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthenticateModel
     */
    public function __invoke(ContainerInterface $container): AuthenticateModel
    {
        $authenticateDAO    = $container->get(AuthenticateDAO::class);
        $authenticateEntity = $container->get(AuthenticateEntity::class);

        return new AuthenticateModel($authenticateDAO, $authenticateEntity);
    }
}
