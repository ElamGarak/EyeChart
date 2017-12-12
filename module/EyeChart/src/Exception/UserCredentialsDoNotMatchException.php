<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) Eye Chart
 */

namespace EyeChart\Exception;

use InvalidArgumentException;
use Throwable;
use Zend\Http\Response;

/**
 * Class UserCredentialsDoNotMatchException
 * @package EyeChart\Exception
 */
class UserCredentialsDoNotMatchException extends InvalidArgumentException
{
    /** Used for testing */
    public const MESSAGE = 'Credentials do not match';

    /** @var string  */
    protected $message = self::MESSAGE;

    /** @var int */
    protected $code = Response::STATUS_CODE_405;

    public function __construct(string $message = '', int $code = Response::STATUS_CODE_405, Throwable $previous = null)
    {
        if (!empty($message)) {
            $this->message = $message;
        }

        parent::__construct($this->message, $code, $previous);
    }
}
