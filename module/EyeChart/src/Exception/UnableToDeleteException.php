<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

/**
 * Class UnableToDeleteException
 * @package EyeChart\Exception
 */
final class UnableToDeleteException extends \Exception
{
    protected $message = 'Unable to delete';
}
