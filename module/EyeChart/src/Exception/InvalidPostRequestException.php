<?php
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/24/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use OutOfBoundsException;
use Throwable;

/**
 * Class InvalidPostRequestException
 * @package EyeChart\Exception
 */
final class InvalidPostRequestException extends OutOfBoundsException
{
    protected $message = "Post key does not exist";

    /**
     * InvalidHeaderRequestException constructor.
     * @param string $key
     * @param int $code
     * @param Throwable $previous
     * @codeCoverageIgnore
     */
    public function __construct(string $key = '', int $code = 500, Throwable $previous = null)
    {
        if (!empty($key)) {
            parent::__construct($this->message, $code, $previous);

            return;
        }

        parent::__construct("{$key} does not exist within incoming post payload");
    }
}
