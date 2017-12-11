<?php
declare(strict_types=1);
/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */
namespace API\V1\Rpc\Login;

use Psr\Container\ContainerInterface;
use EyeChart\Service\Authenticate\AuthenticateService;

/**
 * Class LoginControllerFactory
 * @package API\V1\Rpc\Login
 */
final class LoginControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return LoginController
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): LoginController
    {
        $authenticationService = $container->get(AuthenticateService::class);

        return new LoginController($authenticationService);
    }
}
