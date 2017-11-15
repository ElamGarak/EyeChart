<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/27/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;

/**
 * Class DeprecatedMethodException
 * @package EyeChart\Exception
 */
final class DeprecatedMethodException extends Exception
{
    protected $message =  'has been deprecated.';

    /**
     * DeprecatedMethodException constructor.
     * @param string $method
     */
    public function __construct(string $method)
    {
        $message = "{$method} {$this->message}";

        parent::__construct($message, 405);
    }
}
