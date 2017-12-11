<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Employee;

use Psr\Container\ContainerInterface;
use Zend\Db\Sql\Sql;

/**
 * Class EmployeeDaoFactory
 * @package EyeChart\DAO\Employee
 */
final class EmployeeDaoFactory
{
    /**
     * @param ContainerInterface $container
     * @return EmployeeDao
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): EmployeeDao
    {
        /** @var Sql $sql */
        $sql = new Sql($container->get('db'));

        return new EmployeeDao($sql);
    }

}
