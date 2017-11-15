<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/21/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * Class InvalidDataSourceException
 * @package EyeChart\Exception
 */
final class InvalidDataSourceException extends InvalidArgumentException
{
    /** @var string */
    protected $message = 'Incoming data source';

    /**
     * InvalidDataSourceException constructor.
     * @param string $method
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $method, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message . " passed to {$method}", $code, $previous);
    }
}
