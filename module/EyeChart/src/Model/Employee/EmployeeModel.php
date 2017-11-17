<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Employee;

use EyeChart\DAO\Employee\EmployeeDao;
use EyeChart\Entity\EmployeeEntity;

/**
 * Class EmployeeModel
 * @package EyeChart\Model\Employee
 */
final class EmployeeModel
{
    /** @var EmployeeDao */
    private $employeeDao;

    /** @var EmployeeEntity */
    private $employeeEntity;

    /**
     * EmployeeModel constructor.
     * @param EmployeeDao $employeeDao
     * @param EmployeeEntity $employeeEntity
     */
    public function __construct(EmployeeDao $employeeDao, EmployeeEntity $employeeEntity)
    {
        $this->employeeDao    = $employeeDao;
        $this->employeeEntity = $employeeEntity;
    }

    /**
     * @param string $userId
     * @return EmployeeEntity
     */
    public function getEmployeeRecordByUserId(string $userId): EmployeeEntity
    {
        $results = $this->employeeDao->getEmployeeRecordByUserId($userId);

        $this->employeeEntity->hydrateFromDataBase($results);

        return $this->employeeEntity;
    }
}
