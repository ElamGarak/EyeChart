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
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\SessionMapper;
use Zend\Authentication\Adapter\DbTable\Exception\RuntimeException;
use Zend\Authentication\Exception\ExceptionInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Literal;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateStorageDAO
 * @package EyeChart\DAO\Authenticate
 */
final class AuthenticateStorageDAO implements StorageInterface
{
    /** @var Sql */
    private $sql;

    /** @var SessionEntity */
    private $sessionEntity;

    /** @var mixed[]  */
    private $existingStorage = [];

    /**
     * AuthenticateStorageService constructor.
     * @param Adapter $adapter
     * @param SessionEntity $sessionEntity
     */
    public function __construct(Adapter $adapter, SessionEntity $sessionEntity)
    {
        $this->sql           = new Sql($adapter);
        $this->sessionEntity = $sessionEntity;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        $this->existingStorage = $this->read();

        return empty($this->existingStorage);
    }

    /**
     * Returns the contents of storage
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    public function read()
    {
        $select = $this->sql->select();

        $select->columns([ SessionMapper::DATA ]);

        $select->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::ID, $this->sessionEntity->getId())->and
              ->equalTo(SessionMapper::NAME, $this->sessionEntity->getName());

        $select->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($select);

        $result = $statement->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $row = $resultSet->current();

            if (null != $row) {
                $storage = $row->getArrayCopy();

                return json_decode($storage[SessionMapper::DATA], true);
            }

            return [];
        }

        throw new RuntimeException("Failed to find session record {$this->sessionEntity->getId()} in " . __METHOD__);
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed[] $storage
     * @throws ExceptionInterface
     * @return boolean
     */
    public function write($storage)
    {
        // Unable to implement parameter datatype, due to StorageInterface declaration in ZF
        Assertion::isArray($storage);

        if ($this->isEmpty() === true) {
            return $this->add($storage);
        }

        return $this->merge($storage);
    }

    /**
     * Clears contents from storage
     *
     * @return bool
     */
    public function clear()
    {
        $delete = $this->sql->delete();

        $delete->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::ID, $this->sessionEntity->getId())->and
              ->equalTo(SessionMapper::NAME, $this->sessionEntity->getName());

        $delete->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($delete);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param mixed[] $storage
     * @return bool
     */
    private function add($storage)
    {
        $sessionData = [ $this->sessionEntity->getId() => $storage ];

        $this->sessionEntity->setData(json_encode($sessionData));

        $insert = $this->sql->insert();

        $insert->values([
            SessionMapper::ID       => $this->sessionEntity->getId(),
            SessionMapper::NAME     => $this->sessionEntity->getName(),
            SessionMapper::DATA     => $this->sessionEntity->getData(),
            SessionMapper::MODIFIED => new Literal(time()),
            SessionMapper::LIFETIME => new Literal($this->sessionEntity->getLifeTime())
        ]);

        $insert->into(SessionMapper::TABLE);

        $statement = $this->sql->prepareStatementForSqlObject($insert);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param mixed[] $storage
     * @return bool
     */
    private function merge($storage)
    {
        $sessionData = [ $this->sessionEntity->getId() => $storage ];

        $this->existingStorage = json_encode(array_merge_recursive($this->existingStorage, $sessionData));

        $this->sessionEntity->setData($this->existingStorage);

        $update = $this->sql->update();

        $update->table(SessionMapper::TABLE);

        $update->set([
            SessionMapper::DATA     => $this->sessionEntity->getData(),
            SessionMapper::MODIFIED => time()
        ]);

        $where = new Where();

        $where->equalTo(SessionMapper::ID, $this->sessionEntity->getId())->and
              ->equalTo(SessionMapper::NAME, $this->sessionEntity->getName());

        $update->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param AuthenticateEntity|EntityInterface $authenticateEntity
     * @return bool
     */
    public function prune(EntityInterface $authenticateEntity): bool
    {
        $currentStorage = $this->read();

        if (array_key_exists($this->sessionEntity->getId(), $currentStorage) === false) {
            // User session expired at some point and there is nothing to prune
            return false;
        }

        $userStorage = $currentStorage[$this->sessionEntity->getId()];

        if (array_key_exists($authenticateEntity->getToken(), $userStorage) === true) {
            unset($userStorage[$authenticateEntity->getToken()]);
        }

        if (count($userStorage) != 0) {
            // User has more than one token in the session, update with token removed and return
            return $this->merge($userStorage);
        }

        // User had only one token in the session, clear them out rather than leave an empty, orphan record
        return $this->clear();
    }

    /**
     * @return mixed[]
     */
    public function getEmployeeInformation()
    {
        return $this->getUserStorage();
    }

    /**
     * @return mixed[]
     */
    public function getUserStorage()
    {
        $userSession = $this->read();

        if (array_key_exists($this->sessionEntity->getId(), $userSession)) {
            return $userSession[$this->sessionEntity->getId()];
        }

        return [];
    }

    /**
     * @return int
     */
    public function getSessionLifeTime(): int
    {
        return $this->sessionEntity->getLifeTime();
    }

    /**
     * @param string $token
     */
    public function refresh(string $token): void
    {
        $userStorage                                  = $this->getUserStorage();
        $userStorage[$token][SessionMapper::MODIFIED] = time();

        $this->merge($userStorage);
    }
}
