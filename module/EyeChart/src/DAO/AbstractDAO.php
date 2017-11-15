<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/15/2017
 * (c) 2017
 */

namespace EyeChart\DAO;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Class AbstractDAO
 * @package EyeChart\DAO
 */
class AbstractDAO
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
        $statement = $this->getSqlAdapter()->prepareStatementForSqlObject($select);

        $result = $statement->execute();

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
}
