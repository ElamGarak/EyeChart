<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Entity;

use Assert\Assertion;

/**
 * Class SessionEntity
 * @package EyeChart\Entity
 */
class SessionEntity extends AbstractEntity
{
    /** @var string  */
    protected $sessionId = '';

    /** @var int */
    protected $sessionRecordId = -1;

    /** @var string */
    protected $phpSessionId = '';

    /** @var string */
    protected $sessionUser = '';

    /** @var int */
    protected $lastActive;

    /** @var string */
    protected $token = '';

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId)
    {
        Assertion::maxLength($sessionId, 32);

        $this->sessionId = $sessionId;
    }

    /**
     * @return int
     */
    public function getSessionRecordId(): int
    {
        return $this->sessionRecordId;
    }

    /**
     * @param int $sessionRecordId
     * @return SessionEntity
     */
    public function setSessionRecordId(int $sessionRecordId): SessionEntity
    {
        $this->sessionRecordId = $sessionRecordId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhpSessionId(): string
    {
        return $this->phpSessionId;
    }

    /**
     * @param string $name
     * @return SessionEntity
     */
    public function setPhpSessionId(string $name): SessionEntity
    {
        Assertion::maxLength($name, 32);

        $this->phpSessionId = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionUser(): string
    {
        return $this->sessionUser;
    }

    /**
     * @param string $sessionUser
     * @return SessionEntity
     */
    public function setSessionUser(string $sessionUser): SessionEntity
    {
        Assertion::maxLength($sessionUser, 10);

        $this->sessionUser = $sessionUser;

        return $this;
    }

    /**
     * @return int
     */
    public function getLifetime(): int
    {
        return (int) ini_get('session.gc_maxlifetime');
    }

    /**
     * @return int
     */
    public function getLastActive(): int
    {
        return $this->lastActive;
    }

    /**
     * @param int $lastActive
     * @return SessionEntity
     */
    public function setLastActive(int $lastActive): SessionEntity
    {
        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param mixed $data
     * @return SessionEntity
     */
    public function setToken(string $data): SessionEntity
    {
        $this->token = $data;

        return $this;
    }
}
