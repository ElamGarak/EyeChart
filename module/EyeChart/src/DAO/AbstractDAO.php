<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/15/2017
 * (c) 2017
 */

namespace EyeChart\DAO;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\AbstractPreparableSql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Class AbstractDAO
 * @package EyeChart\DAO
 */
abstract class AbstractDAO
{
    /** @var Sql */
    private $sql;

    /**
     * AuthenticateDAO constructor.
     * @param Sql $sql
     */
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
    }

    /**
     * @param Select $select
     * @param string $returnType
     * @param null $arrayObjectPrototype
     * @return array|\ArrayObject|null
     */
    protected function getResultSingleResult(
        Select $select,
        $returnType = ResultSet::TYPE_ARRAYOBJECT,
        $arrayObjectPrototype = null
    ) {
        $result = $this->executeStatement($select);

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet($returnType, $arrayObjectPrototype);
            $resultSet->initialize($result);

            $row = $resultSet->current();

            if (!is_null($row)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @return Sql
     */
    protected function getSqlAdapter(): Sql
    {
        return $this->sql;
    }

    /**
     * @param AbstractPreparableSql $sql
     * @return ResultInterface
     */
    protected function executeStatement(AbstractPreparableSql $sql): ResultInterface
    {
        $statement = $this->getSqlAdapter()->prepareStatementForSqlObject($sql);

        return $statement->execute();
    }


    /**
     * This is not a good solution.  But until we can find a way for the data to return in the proper types, this will
     * have to do.
     *
     * @param mixed[] $record
     * @return mixed[]
     */
    protected function parseDataTypes(array $record): array
    {
        foreach ($record as $key => $value) {
            switch (true) {
                case (strpos($value, '.') === true) :
                    $record[$key] = (float)$value;

                    break;
                case (is_numeric($value)) :
                    $record[$key] = (int)$value;

                default:
            }
        }

        return $record;
    }
}
