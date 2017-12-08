<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use Assert\Assertion;
use Exception;
use EyeChart\DAO\AbstractDAO;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\MissingSessionException;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\Exception\ExceptionInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Db\Sql\Predicate\Literal;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateStorageDAO
 * @package EyeChart\DAO\Authenticate
 */
class AuthenticateStorageDAO extends AbstractDAO implements StorageInterface
{
    /** @var SessionEntity */
    private $sessionEntity;

    /**
     * AuthenticateStorageService constructor.
     * @param Sql $sql
     * @param SessionEntity $sessionEntity
     */
    public function __construct(Sql $sql, SessionEntity $sessionEntity)
    {
        parent::__construct($sql);

        $this->sessionEntity = $sessionEntity;
    }

    /**
     * Returns true if a session record exists
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        try {
            return empty($this->read());
        } catch (MissingSessionException $exception) {
            return false;
        }
    }

    /**
     * Return token record from session table
     *
     * @return mixed[]
     * @throws MissingSessionException
     */
    public function read(): array
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            SessionMapper::SESSION_RECORD_ID,
            SessionMapper::PHP_SESSION_ID,
            SessionMapper::SESSION_USER,
            SessionMapper::TOKEN,
            SessionMapper::LIFETIME,
            SessionMapper::ACCESSED
        ]);

        $select->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, $this->sessionEntity->getToken());

        $select->where($where);

        $result = parent::getResultSingleResult($select);

        if (empty($result)) {
            throw new MissingSessionException($this->sessionEntity, __METHOD__);
        }

        $results = $this->parseDataTypes($result->getArrayCopy());

        $this->sessionEntity->setSessionRecordId($results[SessionMapper::SESSION_RECORD_ID])
                            ->setSessionUser($results[SessionMapper::SESSION_USER])
                            ->setLastActive($results[SessionMapper::ACCESSED]);

        return $this->parseDataTypes($result->getArrayCopy());
    }

    /**
     * Add token record to session table
     *
     * @param  SessionEntity[] $storage
     * @throws ExceptionInterface
     * @return boolean
     */
    public function write($storage): bool
    {
        // ZF StorageInterface for write does not permit type hint so we can check it here
        Assertion::isArray($storage, 'Session Entity must be in an array');
        // Now ensure only one session was passed
        Assertion::eq(count($storage), 1, 'Storage array may only contain one Session Entity');

        // Now get the session entity and proceed...
        $sessionEntity = $storage[0];

        // Make sure this is an actual session entity
        Assertion::isInstanceOf(
            $sessionEntity,
            SessionEntity::class,
            'Only a session entity may be passed though storage'
        );

        return $this->add($sessionEntity);
    }

    /**
     * Remove token record from session table
     *
     * @return bool
     * @throws Exception
     */
    public function clear(): bool
    {
        if (empty($this->sessionEntity->getToken())) {
            throw new Exception(
                'Session record can not be cleared without a valid token set to the session entity'
            );
        }

        $delete = parent::getSqlAdapter()->delete();
        $delete->from(SessionMapper::TABLE);

        $where = new Where();
        $where->equalTo(SessionMapper::TOKEN, $this->sessionEntity->getToken());

        $delete->where($where);

        $result = parent::executeStatement($delete);

        return $result->isQueryResult();
    }

    /**
     * @param EntityInterface|SessionEntity $sessionEntity
     * @return bool
     */
    private function add(EntityInterface $sessionEntity): bool
    {
        $insert = parent::getSqlAdapter()->insert();

        $insert->values($test = [
            SessionMapper::PHP_SESSION_ID => $sessionEntity->getSessionId(),
            SessionMapper::SESSION_USER   => $sessionEntity->getSessionUser(),
            SessionMapper::TOKEN          => $sessionEntity->getToken(),
            SessionMapper::LIFETIME       => new Literal($this->sessionEntity->getLifetime()),
            SessionMapper::ACCESSED       => new Literal($this->sessionEntity->getLastActive())
        ]);

        $insert->into(SessionMapper::TABLE);

        $result = parent::executeStatement($insert);

        return $result->isQueryResult();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return bool
     */
    public function clearSessionRecord(VOInterface $authenticationVO): bool
    {
        $this->sessionEntity->setToken($authenticationVO->getToken());

        try {
            // Check to see if user is still logged in with current token
            $this->read();
        } catch (MissingSessionException $exception) {
            // User token was removed prior to the check
            return false;
        }

        // User has an active token, clear it now
        $this->clear();

        return true;
    }

    /**
     * @return mixed[]
     * @deprecated
     */
    public function getUserStorage(): array
    {
        $userSession = $this->read();

        if (array_key_exists($this->sessionEntity->getSessionRecordId(), $userSession)) {
            return $userSession[$this->sessionEntity->getSessionRecordId()];
        }

        return [];
    }

    /**
     * @param string $token
     * @return bool
     */
    public function refresh(string $token): bool
    {
        $update = parent::getSqlAdapter()->update();

        $update->table(SessionMapper::TABLE);

        $update->set([
            SessionMapper::ACCESSED => time()
        ]);

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, $token);

        $update->where($where);

        $result = parent::executeStatement($update);

        return $result->isQueryResult();
    }
}
