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
 * Class InvalidHeaderRequestException
 * @package EyeChart\Exception
 */
class InvalidHeaderRequestException extends OutOfBoundsException
{
    protected $message = "Header does not exist";

    /**
     * InvalidHeaderRequestException constructor.
     * @param string $header
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct(string $header = '', int $code = 500, Throwable $previous)
    {
        if (!empty($header)) {
            parent::__construct($this->message, $code, $previous);

            return;
        }

        parent::__construct("{$header} does not exist within incoming headers");
    }
}
