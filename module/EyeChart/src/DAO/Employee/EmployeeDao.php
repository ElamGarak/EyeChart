<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Employee;

use EyeChart\DAO\AbstractDAO;
use EyeChart\Mappers\EmployeeMapper;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

/**
 * Class EmployeeDao
 * @package EyeChart\DAO\Employee
 */
class EmployeeDao extends AbstractDAO
{
    /**
     * @param string $userId
     * @return mixed[]
     */
    public function getEmployeeRecordByUserId(string $userId): array
    {
        $select = parent::getSqlAdapter()->select();

        $select->from(EmployeeMapper::TABLE);

        $where = new Where();
        $where->equalTo(EmployeeMapper::USER_ID, $userId);

        $select->where($where);

        $results = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        if (! empty($results)) {
            $results[EmployeeMapper::EMPLOYEE_ID] = (int) $results[EmployeeMapper::EMPLOYEE_ID];
        }

        return $results;
    }
}
