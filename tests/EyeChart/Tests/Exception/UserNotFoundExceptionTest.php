<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Exception;

use EyeChart\Exception\UserNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class UserNotFoundExceptionTest
 * @package EyeChart\Tests\Exception
 */
final  class UserNotFoundExceptionTest extends TestCase
{
    public function testPassedMessages(): void
    {
        $sut = new UserNotFoundException();

        $this->assertEquals(UserNotFoundException::MESSAGE, $sut->getMessage());

        $userId = 'foo';
        $sut = new UserNotFoundException($userId);

        $this->assertEquals("User {$userId} was not found", $sut->getMessage());
    }
}
