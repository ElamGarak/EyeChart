<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\VO;

use EyeChart\VO\EmailVO;

/**
 * Class EmailVOTest
 * @package EyeChart\Tests\VO
 */
final class EmailVOTest extends VOTest
{
    /** @var string */
    protected static $subjectVONameSpace = EmailVO::class;

    /** @var array[]  */
    protected static $validAssertionValues = [
        'recipient' => ['James.Kirk@starfleet.mil'],
        'subject'   => ['USS Enterprise'],
        'body'      => ['You are hereby requested and required to take command'],
    ];

    /** @var array[]  */
    protected static $invalidAssertionValues = [
        'recipient' => ['James.Kirk'],
        'subject'   => [''],
        'body'      => ['only nine'],
    ];

    public static function setUpBeforeClass(): void
    {
        parent::$subjectVONameSpace     = self::$subjectVONameSpace;
        parent::$validAssertionValues   = self::$validAssertionValues;
        parent::$invalidAssertionValues = self::$invalidAssertionValues;

        parent::setUpBeforeClass();
    }
}
