<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;

final class NoResultsFoundException extends Exception
{
    /** @var string */
    protected $message = 'Unable to find results';

    /** @var int */
    protected $code = 404;
}
