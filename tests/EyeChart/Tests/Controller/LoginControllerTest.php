<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/28/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Controller;

use EyeChart\Controller\LoginController;
use PHPUnit\Framework\TestCase;

/**
 * Class LoginControllerTest
 * @package EyeChart\Tests\Controller
 */
class LoginControllerTest extends TestCase
{
    /** @var string */
    protected static $controllerNameSpace = LoginController::class;

    /** @var string[]  */
    protected static $expectedPostParams = ['key' => 'messages', 'value' => ['foo' => 'bar']];

    public function testIndexAction(): void
    {
        $this->markTestIncomplete("To be handled in a future issue");
    }
}
