<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/12/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use InvalidArgumentException;

/**
 * Class InvalidDateValueException
 * @package EyeChart\Exception
 */
final class InvalidDateValueException extends InvalidArgumentException
{
    protected $message = 'Data value could not be formatted into a date';
}
