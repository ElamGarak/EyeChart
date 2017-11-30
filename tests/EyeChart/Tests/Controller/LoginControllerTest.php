<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 11/28/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Controller;

use EyeChart\Controller\LoginController;
use Zend\View\Model\ViewModel;

/**
 * Class LoginControllerTest
 * @package EyeChart\Tests\Controller
 */
class LoginControllerTest extends AbstractControllerTest
{
    /** @var string */
    protected static $controllerNameSpace = LoginController::class;

    /** @var string[]  */
    protected static $expectedPostParams = ['key' => 'messages', 'value' => ['foo' => 'bar']];

    public static function setUpBeforeClass(): void
    {
        parent::$controllerNameSpace = self::$controllerNameSpace;
        parent::$expectedPostParams  = self::$expectedPostParams;

        parent::setUpBeforeClass();
    }

    public function testIndexAction(): void
    {
        /** @var ViewModel $result */
        $result = $this->controller->indexAction();

        /** @noinspection PhpUndefinedFieldInspection */
        $actual = $result->getVariables()->messages;

        $this->assertEquals(json_encode(self::$expectedPostParams['value']), $actual);
    }
}
