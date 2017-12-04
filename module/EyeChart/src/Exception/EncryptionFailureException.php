<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/3/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use Exception;
use Throwable;
use Zend\Http\Response;

/**
 * Class EncryptionFailureException
 * @package EyeChart\Exception
 */
final class EncryptionFailureException extends Exception
{
    protected $message = "Unable to encrypt";

    /** @var int */
    protected $code = Response::STATUS_CODE_400;

    /**
     * EncryptionFailureException constructor.
     * @param string $dataToEncrypt
     * @param string $key
     * @param string $errorString
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $dataToEncrypt,
        string $key,
        string $errorString = '',
        int $code = Response::STATUS_CODE_400,
        Throwable $previous = null
    ) {
        $this->message .= " {$dataToEncrypt} using {$key}";

        if (!empty($message)) {
            $this->message .= " {$errorString}";
        }

        parent::__construct($this->message, $code, $previous);
    }
}
