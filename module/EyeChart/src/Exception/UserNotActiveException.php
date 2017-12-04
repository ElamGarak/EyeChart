<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) Eye Chart
 */

namespace EyeChart\Exception;

use Exception;
use Throwable;
use Zend\Http\Response;

/**
 * Class UserNotActiveException
 * @package EyeChart\Exception
 */
class UserNotActiveException extends Exception
{
    /** @var string  */
    protected $message = "User not active";

    /** @var int */
    protected $code = Response::STATUS_CODE_412;

    public function __construct(string $userId = '', int $code = Response::STATUS_CODE_412, Throwable $previous = null)
    {
        if (!empty($userId)) {
            $this->message = "User [$userId] is not active";
        }

        parent::__construct($this->message, $code, $previous);
    }
}
