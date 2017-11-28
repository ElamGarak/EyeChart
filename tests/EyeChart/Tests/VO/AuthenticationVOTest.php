<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\VO;

use EyeChart\VO\AuthenticationVO;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class AuthenticationVOTest
 * @package EyeChart\Tests\VO
 */
final class AuthenticationVOTest extends TestCase
{
    /** @var string[] */
    private static $expectedValues = [
        'username' => 'kirk',
        'password' => '000 Destruct 0',
        'token'    => ''
    ];

    /** @var AuthenticationVO */
    private static $subjectVO;

    public static function setUpBeforeClass(): void
    {
        self::$expectedValues['token'] = str_repeat('a', 36);

        self::$subjectVO = AuthenticationVO::build()->setUsername(self::$expectedValues['username'])
                                                    ->setPassword(self::$expectedValues['password'])
                                                    ->setToken(self::$expectedValues['token']);
    }

    /**
     * Gratuitous for now but may become useful once a common fixture is built and the getters can be used as part of
     * other tests.
     */
    public function testGetters(): void
    {
        foreach (self::$expectedValues as $key => $expectedValue) {
            $getter = 'get' . ucfirst($key);

            $actual = self::$subjectVO->{$getter}();

            $this->assertEquals($expectedValue, $actual);
        }
    }

    /**
     * @param string $setter
     * @param string $value
     * @dataProvider provideInvalidDependenciesForAssertions
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testSettersThrowAssertionExceptions(string $setter, string $value): void
    {
        AuthenticationVO::build()->{$setter}($value);
    }

    /**
     * @return array[]
     */
    public function provideInvalidDependenciesForAssertions(): array
    {
        return [
            ['setUserName', ''],
            ['setPassword', ''],
            ['setToken', str_repeat('a', 35)],
            ['setToken', str_repeat('a', 38)],
        ];
    }

    /**
     * @param string $setter
     * @param mixed $value
     *
     * @dataProvider provideInvalidDependencies
     * @expectedException \TypeError
     */
    public function testDependencyTypeHintWasSet(string $setter, $value): void
    {
        AuthenticationVO::build()->{$setter}($value);
    }

    /**
     * @return array[]
     */
    public function provideInvalidDependencies(): array
    {
        return [
            ['setUserName', null],
            ['setUserName', 1],
            ['setUserName', new stdClass()],
            ['setUserName', []],
            ['setUserName', 1.1],
            ['setUserName', true],
            ['setPassword', null],
            ['setPassword', 1],
            ['setPassword', new stdClass()],
            ['setPassword', []],
            ['setPassword', 1.1],
            ['setPassword', true],
            ['setToken', null],
            ['setToken', 1],
            ['setToken', new stdClass()],
            ['setToken', []],
            ['setToken', 1.1],
            ['setToken', true],
        ];
    }
}
