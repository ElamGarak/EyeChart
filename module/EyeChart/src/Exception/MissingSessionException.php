<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/18/2017
 * (c) 2017
 */

namespace EyeChart\Exception;

use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use RuntimeException;
use Throwable;

/**
 * Class MissingSessionException
 * @package EyeChart\Exception
 */
class MissingSessionException extends RuntimeException
{
    protected $message = 'Failed to find session record';

    /**
     * MissingSessionException constructor.
     * @param EntityInterface|SessionEntity $sessionEntity
     * @param string $method
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        EntityInterface $sessionEntity,
        string $method,
        int $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct(
            "{$this->message} by token {$sessionEntity->getToken()} in {$method}",
            $code,
            $previous
        );
    }
}
