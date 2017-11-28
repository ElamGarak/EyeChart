<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests\Logout;

use API\Tests\ApiControllerWithAuthenticationTest;
use API\V1\Rpc\Logout\LogoutController;
use EyeChart\Service\Authenticate\AuthenticateService;
use EyeChart\VO\AuthenticationVO;

/**
 * Class LogoutControllerTest
 * @package API\Tests\Logout
 */
final class LogoutControllerTest extends ApiControllerWithAuthenticationTest
{
    /** @var string */
    public static $controllerName = LogoutController::class;

    /** @var string */
    public static $serviceName = AuthenticateService::class;

    /** @var string  */
    public static $voName = AuthenticationVO::class;

    /** @var string */
    public static $actionMethod = 'logoutAction';

    /** @var bool */
    public static $runTestPayload = false;

    /**
     * 'nameOfServiceMethod(s)ToBeMocked' => expected return
     *
     * @var mixed[]
     */
    public static $serviceMethods = [
        'logout' => ['foo']
    ];

    public static $expectedReturn = [
        'messages' => ['foo']
    ];

    public static function setUpBeforeClass(): void
    {
        parent::$controllerName = self::$controllerName;
        parent::$serviceName    = self::$serviceName;
        parent::$voName         = self::$voName;
        parent::$serviceMethods = self::$serviceMethods;
        parent::$actionMethod   = self::$actionMethod;
        parent::$runTestPayload = self::$runTestPayload;
        parent::$expectedReturn = self::$expectedReturn;

        parent::setUpBeforeClass();
    }
}
