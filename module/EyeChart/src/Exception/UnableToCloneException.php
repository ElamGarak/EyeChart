<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/12/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

/**
 * Class UnableToCloneException
 * @package EyeChart\Exception
 */
final class UnableToCloneException extends \Exception
{
    protected $message = 'Cloning is not allowed';
}
