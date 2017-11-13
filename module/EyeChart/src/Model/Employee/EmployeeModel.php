<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Employee;

use EyeChart\Entity\EmployeeEntity;
use EyeChart\VO\LoginVO;

/**
 * Class EmployeeModel
 * @package EyeChart\Model\Employee
 */
final class EmployeeModel
{
    /**
     * EmployeeModel constructor.
     */
    public function __construct()
    {
        // Stub
    }

    /**
     * @param LoginVO $loginVO
     * @return EmployeeEntity
     */
    public function getEmployeeRecordByCredentials(LoginVO $loginVO): EmployeeEntity
    {
        // Stub
        return new EmployeeEntity();
    }
}
