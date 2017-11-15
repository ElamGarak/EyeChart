<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/13/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

/**
 * Class UnableToUpdateException
 * @package EyeChart\Exception
 */
final class UnableToUpdateException extends \Exception
{
    protected $message = 'Unable to update';
}
