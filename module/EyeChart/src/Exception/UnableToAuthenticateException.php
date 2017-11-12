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
use EyeChart\VO\LoginVO;
use EyeChart\VO\VOInterface;
use Throwable;

/**
 * Class UnableToAuthenticateException
 * @package EyeChart\Exception
 */
final class UnableToAuthenticateException extends Exception
{
    /** @var string  */
    protected $message = 'Unable to authenticate user.';

    /** @var int  */
    protected $code = 401;

    /**
     * UnableToAuthenticateException constructor.
     * @param VOInterface|LoginVO $loginVO
     * @param Throwable|null $previous
     */
    public function __construct(VOInterface $loginVO, Throwable $previous = null)
    {
        parent::__construct("Unable to authenticate {$loginVO->getUserName()}", $this->code, $previous);
    }
}
