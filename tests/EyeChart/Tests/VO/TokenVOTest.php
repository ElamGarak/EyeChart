<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\VO;

use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\TokenVO;

/**
 * Class TokenVOTest
 * @package EyeChart\Tests\VO
 */
final class TokenVOTest extends VOTest
{
    /** @var string */
    protected static $subjectVONameSpace = TokenVO::class;

    /** @var array[]  */
    protected static $validAssertionValues = [];

    /** @var array[]  */
    protected static $invalidAssertionValues = [];

    public static function setUpBeforeClass(): void
    {
        parent::$subjectVONameSpace = self::$subjectVONameSpace;

        parent::$validAssertionValues = [
            'token' => [
                str_repeat('a', AuthenticateMapper::TOKEN_LENGTH)
            ]
        ];

        parent::$invalidAssertionValues = [
            'token' => [
                str_repeat('a', AuthenticateMapper::TOKEN_LENGTH + 1),
                str_repeat('a', AuthenticateMapper::TOKEN_LENGTH - 1)
            ]
        ];

        parent::setUpBeforeClass();
    }
}
