<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Exception;

use EyeChart\Exception\UserNotActiveException;
use PHPUnit\Framework\TestCase;

/**
 * Class UserNotActiveExceptionTest
 * @package EyeChart\Tests\Exception
 */
final class UserNotActiveExceptionTest extends TestCase
{
    public function testPassedMessages(): void
    {
        $sut = new UserNotActiveException();

        $this->assertEquals(UserNotActiveException::MESSAGE, $sut->getMessage());

        $userId = 'foo';
        $sut = new UserNotActiveException($userId);

        $this->assertEquals("User {$userId} is not active", $sut->getMessage());
    }
}
