<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Employee;

use EyeChart\Mappers\EmployeeMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class EmployeeDao
 * @package EyeChart\DAO\Employee
 */
class EmployeeDao
{
    /** @var Sql */
    private $sql;

    /**
     * AuthenticateDAO constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->sql = new Sql($adapter);
    }

    /**
     * @param string $userId
     * @return mixed[]
     */
    public function getEmployeeRecordByUserId(string $userId): array
    {
        $select = $this->sql->select();

        $select->from(EmployeeMapper::TABLE);

        $where = new Where();
        $where->equalTo(EmployeeMapper::USER_ID, $userId);

        $select->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($select);

        $result = $statement->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet(ResultSet::TYPE_ARRAY);
            $resultSet->initialize($result);

            $row = $resultSet->current();

            if (! is_null($row)) {
                $row[EmployeeMapper::EMPLOYEE_ID] = (int) $row[EmployeeMapper::EMPLOYEE_ID];

                return $row;
            }
        }

        return [];
    }
}
