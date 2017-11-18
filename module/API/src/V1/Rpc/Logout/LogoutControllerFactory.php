<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */
namespace API\V1\Rpc\Logout;

use EyeChart\Entity\AuthenticateEntity;
use Psr\Container\ContainerInterface;
use EyeChart\Service\Authenticate\AuthenticateService;

/**
 * Class LogoutControllerFactory
 * @package API\V1\Rpc\Logout
 */
class LogoutControllerFactory
{
    /**
     *
     * @param ContainerInterface $container
     * @return LogoutController
     */
    public function __invoke(ContainerInterface $container): LogoutController
    {
        $authenticateService = $container->get(AuthenticateService::class);
        $authenticateEntity  = $container->get(AuthenticateEntity::class);

        return new LogoutController($authenticateService, $authenticateEntity);
    }
}
