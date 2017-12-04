<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) 2017
 */
namespace EyeChart\Tests\VO\Authentication;

use EyeChart\Tests\VO\VOTest;
use EyeChart\VO\Authentication\CredentialsVO;

/**
 * Class CredentialsVOTest
 * @package EyeChart\Tests\VO\Authentication
 */
final class CredentialsVOTest extends VOTest
{
    /** @var string */
    protected static $subjectVONameSpace = CredentialsVO::class;

    /** @var array[] */
    protected static $validAssertionValues = [
        'credentials' => []
    ];

    /** @var array[] */
    protected static $invalidAssertionValues = [
        'credentials' => ['foo']
    ];

    public static function setUpBeforeClass(): void
    {
        self::$validAssertionValues['credentials'] = [ str_repeat('a', 512) ];

        parent::$subjectVONameSpace     = self::$subjectVONameSpace;
        parent::$validAssertionValues   = self::$validAssertionValues;
        parent::$invalidAssertionValues = self::$invalidAssertionValues;

        parent::setUpBeforeClass();
    }
}
