<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Exception;

use EyeChart\Exception\UserCredentialsDoNotMatchException;
use PHPUnit\Framework\TestCase;

/**
 * Class UserCredentialsDoNotMatchExceptionTest
 * @package EyeChart\Tests\Exception
 */
final class UserCredentialsDoNotMatchExceptionTest extends TestCase
{
    public function testPassedMessages(): void
    {
        $sut = new UserCredentialsDoNotMatchException();

        $this->assertEquals(UserCredentialsDoNotMatchException::MESSAGE, $sut->getMessage());

        $sut = new UserCredentialsDoNotMatchException('foo');

        $this->assertEquals('foo', $sut->getMessage());
    }
}
