<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use EyeChart\DAO\AbstractDAO;
use EyeChart\Exception\NoResultsFoundException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateDAO
 * @package EyeChart\DAL\DAO\Authenticate
 */
class AuthenticateDAO extends AbstractDAO
{
    /**
     * @param VOInterface|AuthenticationVO $vo
     * @return string[]
     */
    public function getByteCodeAndTag(VOInterface $vo): array
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::BYTE_CODE,
            AuthenticateMapper::TAG
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername())->and
              ->equalTo(AuthenticateMapper::IS_ACTIVE, true);

        $select->where($where);

        $results = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        if (empty($results)) {
            throw new NoResultsFoundException();
        }

        return $results;
    }

    /**
     * @param AuthenticationVO|VOInterface $vo
     * @return bool
     */
    public function checkCredentials(VOInterface $vo): bool
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::USER_NAME,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername())->and
              ->equalTo(AuthenticateMapper::BYTE_CODE, $vo->getByteCode())->and
              ->equalTo(AuthenticateMapper::CREDENTIALS, $vo->getCredentials())->and
              ->equalTo(AuthenticateMapper::TAG, $vo->getTag())->and
              ->equalTo(AuthenticateMapper::IS_ACTIVE, true);

        $select->where($where);

        $result = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        return (!is_null($result));
    }

    /**
     * @param AuthenticationVO|VOInterface $vo
     * @return bool
     */
    public function isUserActive(VOInterface $vo): bool
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::IS_ACTIVE,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername());

        $select->where($where);

        $result = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        $results = $this->parseDataTypes($result);

        return (bool) $results[AuthenticateMapper::IS_ACTIVE];
    }
}
