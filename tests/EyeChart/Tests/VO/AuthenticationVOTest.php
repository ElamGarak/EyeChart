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
use EyeChart\VO\AuthenticationVO;

/**
 * Class AuthenticationVOTest
 * @package EyeChart\Tests\VO
 */
final class AuthenticationVOTest extends VOTest
{
    /** @var string */
    protected static $subjectVONameSpace = AuthenticationVO::class;

    /** @var array[]  */
    protected static $validAssertionValues = [
        'username' => ['kirk'],
        'password' => ['000 Destruct 0'],
    ];

    /** @var array[]  */
    protected static $invalidAssertionValues = [
        'username' => [''],
        'password' => ['']
    ];

    public static function setUpBeforeClass(): void
    {
        self::$validAssertionValues['token'][] = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH);

        self::$invalidAssertionValues['token'][] = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH + 1);
        self::$invalidAssertionValues['token'][] = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH - 1);

        parent::$subjectVONameSpace     = self::$subjectVONameSpace;
        parent::$validAssertionValues   = self::$validAssertionValues;
        parent::$invalidAssertionValues = self::$invalidAssertionValues;

        parent::setUpBeforeClass();
    }
}
