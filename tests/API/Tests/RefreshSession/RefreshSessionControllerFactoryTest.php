<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests\RefreshSession;

use API\Tests\ApiControllerFactoryTest;
use API\V1\Rpc\RefreshSession\RefreshSessionController;
use API\V1\Rpc\RefreshSession\RefreshSessionControllerFactory;
use EyeChart\Service\Authenticate\AuthenticateStorageService;
use League\Tactician\CommandBus;

/**
 * Class RefreshSessionFactoryTest
 * @package API\Tests\RefreshSession
 */
final class RefreshSessionControllerFactoryTest extends ApiControllerFactoryTest
{
    /** @var string */
    public static $factoryName = RefreshSessionControllerFactory::class;

    /** @var string */
    public static $controllerName = RefreshSessionController::class;

    public static $containerParams = [
        AuthenticateStorageService::class,
        CommandBus::class
    ];

    public static function setUpBeforeClass(): void
    {
        parent::$factoryName     = self::$factoryName;
        parent::$controllerName  = self::$controllerName;
        parent::$containerParams = self::$containerParams;

        parent::setUpBeforeClass();
    }
}
