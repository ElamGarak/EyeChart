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
use EyeChart\VO\AuthenticationVO;

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
     * @param AuthenticationVO $authenticationVO
     * @return EmployeeEntity
     */
    public function getEmployeeRecordByCredentials(AuthenticationVO $authenticationVO): EmployeeEntity
    {
        // Stub
        return new EmployeeEntity();
    }
}
