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
     */
    public function setId(string $id): void
    {
        Assertion::maxLength($id, 32);

        $this->id = $id;
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
     */
    public function setName(string $name): void
    {
        Assertion::maxLength($name, 32);

        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLifeTime(): int
    {
        return $this->lifeTime;
    }

    /**
     * @param int|string $lifeTime
     */
    public function setLifeTime(int $lifeTime): void
    {
        $this->lifeTime = $lifeTime;
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
     */
    public function setModified(int $modified): void
    {
        $this->modified = $modified;
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
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }
}
