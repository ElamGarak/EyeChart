<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests\CheckSessionStatus;

use API\Tests\ApiControllerWithAuthenticationTest;
use API\V1\Rpc\CheckSessionStatus\CheckSessionStatusController;
use EyeChart\Service\Authenticate\AuthenticateStorageService;
use EyeChart\VO\TokenVO;

/**
 * Class CheckSessionStatusControllerTest
 * @package API\Tests\CheckSessionStatus
 */
final class CheckSessionStatusControllerTest extends ApiControllerWithAuthenticationTest
{
    /** @var string */
    public static $controllerName = CheckSessionStatusController::class;

    /** @var string */
    public static $serviceName = AuthenticateStorageService::class;

    /** @var string  */
    public static $voName = TokenVO::class;

    /** @var string */
    public static $actionMethod = 'checkSessionStatusAction';

    /** @var bool */
    public static $runTestPayload = false;

    /**
     * 'nameOfServiceMethod(s)ToBeMocked' => expected return
     *
     * @var mixed[]
     */
    public static $serviceMethods = [
        'getUserSessionByToken' => []
    ];

    public static $expectedReturn = [];

    public static function setUpBeforeClass(): void
    {
        parent::$controllerName = self::$controllerName;
        parent::$serviceName    = self::$serviceName;
        parent::$voName         = self::$voName;
        parent::$serviceMethods = self::$serviceMethods;
        parent::$actionMethod   = self::$actionMethod;
        parent::$runTestPayload = self::$runTestPayload;
        parent::$expectedReturn = self::$expectedReturn;
        parent::$expectedPayload = [
            'token' => str_repeat('a', 36)
        ];

        parent::setUpBeforeClass();
    }
}
