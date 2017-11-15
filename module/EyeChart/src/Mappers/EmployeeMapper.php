<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Mappers;

use EyeChart\Entity\AbstractEntity;

/**
 * Class EmployeeMapper
 * @package EyeChart\Mappers
 */
class EmployeeMapper extends AbstractEntity
{
    public const SCHEMA = '';
    public const TABLE  = 'employees';
    public const ALIAS  = 'e';

    public const EMPLOYEE_ID   = 'EmployeeId';
    public const USER_ID       = 'UserName';
    public const FIRST_NAME    = 'FirstName';
    public const LAST_NAME     = 'LastName';
    public const EMAIL_ADDRESS = 'EmailAddress';
}
