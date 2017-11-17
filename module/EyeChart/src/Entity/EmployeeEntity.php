<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Entity;
/**
 * Class EmployeeEntity
 * @package EyeChart\Entity
 */
class EmployeeEntity extends AbstractEntity
{
    /** @var int  */
    protected $employeeId = -1;

    /** @var string  */
    protected $username = '';

    /** @var string  */
    protected $firstName = '';

    /** @var string  */
    protected $lastName = '';

    /** @var string  */
    protected $emailAddress = '';

    /** @var string  */
    protected $created = '';

    /** @var string  */
    protected $createdBy = '';

    /** @var string  */
    protected $modified = '';

    /** @var string  */
    protected $modifiedBy = '';

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getModified(): string
    {
        return $this->modified;
    }

    /**
     * @return string
     */
    public function getModifiedBy(): string
    {
        return $this->modifiedBy;
    }

    /**
     * @param int $employeeId
     * @return EmployeeEntity
     */
    public function setEmployeeId(int $employeeId): EmployeeEntity
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * @param string $username
     * @return EmployeeEntity
     */
    public function setUsername(string $username): EmployeeEntity
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $firstName
     * @return EmployeeEntity
     */
    public function setFirstName(string $firstName): EmployeeEntity
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     * @return EmployeeEntity
     */
    public function setLastName(string $lastName): EmployeeEntity
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string $emailAddress
     * @return EmployeeEntity
     */
    public function setEmailAddress(string $emailAddress): EmployeeEntity
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @param string $created
     * @return EmployeeEntity
     */
    public function setCreated(string $created): EmployeeEntity
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @param string $createdBy
     * @return EmployeeEntity
     */
    public function setCreatedBy(string $createdBy): EmployeeEntity
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @param string $modified
     * @return EmployeeEntity
     */
    public function setModified(string $modified): EmployeeEntity
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @param string $modifiedBy
     * @return EmployeeEntity
     */
    public function setModifiedBy(string $modifiedBy): EmployeeEntity
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }
}
