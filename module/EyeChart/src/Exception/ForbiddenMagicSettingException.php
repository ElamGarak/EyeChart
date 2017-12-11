<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Exception;

use Exception;
use Zend\Http\Response;

/**
 * Class ForbiddenMagicSettingException
 * @package EyeChart\Exception
 */
class ForbiddenMagicSettingException extends Exception
{
    protected $message = 'Magic settings is not allowed for this class';

    protected $code = Response::STATUS_CODE_403;
}
