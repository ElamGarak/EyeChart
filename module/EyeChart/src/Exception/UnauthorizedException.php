<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/15/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;

/**
 * Class UnauthorizedException
 * @package EyeChart\Exception
 */
class UnauthorizedException extends Exception
{
    protected $message = 'Unauthorized Access';
    protected $code    = 401;
}
