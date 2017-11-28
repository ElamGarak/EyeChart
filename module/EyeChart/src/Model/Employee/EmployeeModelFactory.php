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
use Psr\Container\ContainerInterface;

/**
 * Class EmployeeModelFactory
 * @package EyeChart\Model\Email
 * @codeCoverageIgnore
 */
final class EmployeeModelFactory
{
    /**
     * @param ContainerInterface $container
     * @return EmployeeModel
     */
    public function __invoke(ContainerInterface $container): EmployeeModel
    {
        return new EmployeeModel(
            $container->get(EmployeeDao::class),
            $container->get(EmployeeEntity::class)
        );
    }
}
