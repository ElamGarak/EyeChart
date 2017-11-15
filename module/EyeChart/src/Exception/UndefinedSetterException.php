<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;
use Throwable;

/**
 * Class UndefinedSetterException
 * @package EyeChart\Exception
 */
final class UndefinedSetterException extends Exception
{
    /**
     * UndefinedSetterException constructor.
     * @param string $setterName
     * @param string $entityName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $setterName, string $entityName, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("{$setterName} has not been defined within {$entityName}", $code, $previous);
    }
}
