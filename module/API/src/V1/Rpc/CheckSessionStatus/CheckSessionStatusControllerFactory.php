<?php
declare(strict_types=1);

/**
 * Created by Apigility.
 * Date: 10/13/2017
 * (c) 2017
 */

namespace API\V1\Rpc\CheckSessionStatus;

use EyeChart\Service\Authenticate\AuthenticateStorageService;
use Psr\Container\ContainerInterface;

/**
 * Class CheckSessionStatusControllerFactory
 * @package API\V1\Rpc\CheckSessionStatus
 */
class CheckSessionStatusControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return CheckSessionStatusController
     */
    public function __invoke(ContainerInterface $container): CheckSessionStatusController
    {
        $service = $container->get(AuthenticateStorageService::class);

        return new CheckSessionStatusController($service);
    }
}
