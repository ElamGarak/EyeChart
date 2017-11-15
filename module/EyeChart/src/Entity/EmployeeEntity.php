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
    private $employeeId = -1;

    /**
     * @return int
     */
    public function getEmployeeId(): int
    {
        return $this->employeeId;
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
}
