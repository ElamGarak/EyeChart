<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests\Logout;

use API\Tests\ApiControllerFactoryTest;
use API\V1\Rpc\Logout\LogoutController;
use API\V1\Rpc\Logout\LogoutControllerFactory;
use EyeChart\Service\Authenticate\AuthenticateService;

/**
 * Class LogoutControllerFactoryTest
 * @package API\Tests\Logout
 */
final class LogoutControllerFactoryTest extends ApiControllerFactoryTest
{
    /** @var string */
    public static $factoryName = LogoutControllerFactory::class;

    /** @var string */
    public static $controllerName = LogoutController::class;

    public static $containerParams = [
        AuthenticateService::class
    ];

    public static function setUpBeforeClass(): void
    {
        parent::$factoryName     = self::$factoryName;
        parent::$controllerName  = self::$controllerName;
        parent::$containerParams = self::$containerParams;

        parent::setUpBeforeClass();
    }
}
