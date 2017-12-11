<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Command\Commands;

use EyeChart\Command\Commands\SessionRefreshCommand;
use EyeChart\VO\Authentication\AuthenticationVO;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractCommandTest
 * @package EyeChart\Tests\Command\Commands
 */
class AbstractCommandTest extends TestCase
{
    /**
     * @expectedException \EyeChart\Exception\ForbiddenMagicSettingException
     */
    public function testMagicSetterOverrideThrowsException(): void
    {
        $sut = new SessionRefreshCommand(AuthenticationVO::build());

        /** @noinspection PhpUndefinedFieldInspection */
        $sut->foo = 'bar';
    }
}
