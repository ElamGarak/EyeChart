<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests\CheckSessionStatus;

use API\Tests\ApiControllerFactoryTest;
use API\V1\Rpc\CheckSessionStatus\CheckSessionStatusController;
use API\V1\Rpc\CheckSessionStatus\CheckSessionStatusControllerFactory;
use EyeChart\Service\Authenticate\AuthenticateStorageService;

/**
 * Class CheckSessionStatusFactoryTest
 * @package API\Tests\CheckSessionStatus
 */
final class CheckSessionStatusFactoryTest extends ApiControllerFactoryTest
{
    /** @var string */
    public static $factoryName = CheckSessionStatusControllerFactory::class;

    /** @var string */
    public static $controllerName = CheckSessionStatusController::class;

    public static $containerParams = [
        AuthenticateStorageService::class
    ];

    public static function setUpBeforeClass(): void
    {
        parent::$factoryName     = self::$factoryName;
        parent::$controllerName  = self::$controllerName;
        parent::$containerParams = self::$containerParams;

        parent::setUpBeforeClass();
    }
}
