<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateDAO
 * @package EyeChart\DAL\DAO\Authenticate
 */
class AuthenticateDAO
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
     * @param AuthenticationVO|VOInterface $vo
     * @return bool
     */
    public function checkCredentials(VOInterface $vo): bool
    {
        $select = $this->sql->select();

        $select->columns([
            AuthenticateMapper::USER_NAME,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername())->and
              ->equalTo(AuthenticateMapper::PASSWORD, $vo->getPassword());

        $select->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($select);

        $result = $statement->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet(ResultSet::TYPE_ARRAY);
            $resultSet->initialize($result);

            $row = $resultSet->current();

            return (! is_null($row));
        }

        return false;
    }

    /**
     * TODO
     * @param string $userId
     * @return bool
     */
    public function isUserValid(string $userId): bool
    {
        return false;
    }
}
