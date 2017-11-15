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
    /** @var string */
    protected $id = '';

    /** @var string */
    protected $name = '';

    /** @var int */
    protected $lifeTime = -1;

    /** @var int */
    protected $modified = -1;

    /** @var string */
    protected $data = '';

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return SessionEntity
     */
    public function setId(string $id): SessionEntity
    {
        Assertion::maxLength($id, 32);

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SessionEntity
     */
    public function setName(string $name): SessionEntity
    {
        Assertion::maxLength($name, 32);

        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getLifeTime(): int
    {
        return $this->lifeTime;
    }

    /**
     * @param int $lifeTime
     * @return SessionEntity
     */
    public function setLifeTime(int $lifeTime): SessionEntity
    {
        $this->lifeTime = $lifeTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getModified(): int
    {
        return $this->modified;
    }

    /**
     * @param int $modified
     * @return SessionEntity
     */
    public function setModified(int $modified): SessionEntity
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return SessionEntity
     */
    public function setData(string $data): SessionEntity
    {
        $this->data = $data;

        return $this;
    }
}
