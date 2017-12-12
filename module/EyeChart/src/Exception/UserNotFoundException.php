<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) Eye Chart
 */

namespace EyeChart\Exception;

use OutOfBoundsException;
use Throwable;
use Zend\Http\Response;

/**
 * Class UserNotFoundException
 * @package EyeChart\Exception
 */
final class UserNotFoundException extends OutOfBoundsException
{
    /** Used for testing */
    public const MESSAGE = 'User not found';

    /** @var string  */
    protected $message = self::MESSAGE;

    /** @var int */
    protected $code = Response::STATUS_CODE_404;

    public function __construct(string $userId = '', int $code = Response::STATUS_CODE_404, Throwable $previous = null)
    {
        if (!empty($userId)) {
            $this->message = "User {$userId} was not found";
        }

        parent::__construct($this->message, $code, $previous);
    }
}
