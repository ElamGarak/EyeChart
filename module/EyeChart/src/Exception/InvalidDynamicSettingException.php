<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/15/2017
 * (c) 2017
 */

namespace EyeChart\Exception;
/**
 * Class InvalidDynamicSettingException
 * @package EyeChart\Exception
 */
final class InvalidDynamicSettingException extends \Exception
{
    protected $message = 'Dynamic setting not allowed';
}
