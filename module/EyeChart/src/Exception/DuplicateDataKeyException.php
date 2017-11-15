<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;

/**
 * Class DuplicateDataKeyException
 * @package EyeChart\Exception
 */
final class DuplicateDataKeyException extends Exception
{
    protected $message = 'Duplicate data key error';
}
