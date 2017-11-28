<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\VO;

use EyeChart\VO\TokenVO;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class TokenVOTest
 * @package EyeChart\Tests\VO
 */
final class TokenVOTest extends TestCase
{
    /** @var string */
    private static $expectedToken = '';

    /** @var TokenVO */
    private static $subjectVO;

    public static function setUpBeforeClass(): void
    {
        self::$expectedToken = str_repeat('a', 36);
        self::$subjectVO     = new TokenVO(self::$expectedToken);
    }

    public function testGetToken(): void
    {
        $actual = self::$subjectVO->getToken();

        $this->assertEquals(self::$expectedToken, $actual);
    }

    /**
     * @param string $value
     * @dataProvider provideInvalidDependenciesForAssertions
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testSetTokenThrowsAssertionExceptions(string $value): void
    {
        new TokenVO($value);
    }


    /**
     * @return array[]
     */
    public function provideInvalidDependenciesForAssertions(): array
    {
        return [
            [str_repeat('a', 35)],
            [str_repeat('a', 38)],
        ];
    }

    /**
     * @param mixed $value
     * @dataProvider provideInvalidDependencies
     * @expectedException \TypeError
     */
    public function testDependencyTypeHintWasSet($value): void
    {
        new TokenVO($value);
    }

    /**
     * @return array[]
     */
    public function provideInvalidDependencies(): array
    {
        return [
            [null],
            [1],
            [new stdClass()],
            [[]],
            [1.1],
            [true]
        ];
    }
}
