<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Command\Commands;

use EyeChart\Command\Commands\AbstractCommand;
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
        $sut = new AbstractCommand();

        /** @noinspection PhpUndefinedFieldInspection */
        $sut->foo = 'bar';
    }
}
