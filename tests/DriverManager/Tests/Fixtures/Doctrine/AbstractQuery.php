<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 10/23/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\ORM\AbstractQuery as DoctrineAbstractQuery;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class AbstractQuery
 * @package Doctrine
 *
 * See notes in \DriverManager\Doctrine\Fixtures\Query
 */
abstract class AbstractQuery
{
    const HYDRATE_OBJECT        = DoctrineAbstractQuery::HYDRATE_OBJECT;
    const HYDRATE_ARRAY         = DoctrineAbstractQuery::HYDRATE_ARRAY;
    const HYDRATE_SCALAR        = DoctrineAbstractQuery::HYDRATE_SCALAR;
    const HYDRATE_SINGLE_SCALAR = DoctrineAbstractQuery::HYDRATE_SINGLE_SCALAR;
    const HYDRATE_SIMPLEOBJECT  = DoctrineAbstractQuery::HYDRATE_SIMPLEOBJECT;

    /**
     * AbstractQuery constructor.
     * @return AbstractQuery
     */
    public function __construct()
    {
        return $this;
    }

    /**
     *
     * @param boolean $cacheable
     * @return self
     */
    public function setCacheable($cacheable)
    {
        return null;
    }

    /**
     * @return boolean
     */
    public function isCacheable()
    {
        return null;
    }

    /**
     * @param string $cacheRegion
     * @return self
     */
    public function setCacheRegion($cacheRegion)
    {
        return null;
    }

    /**
     * @return self
     */
    public function getCacheRegion()
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getLifetime()
    {
        return null;
    }

    /**
     * @param integer $lifetime
     *
     * @return self
     */
    public function setLifetime($lifetime)
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getCacheMode()
    {
        return null;
    }

    /**
     * @param integer $cacheMode
     * @return self
     */
    public function setCacheMode($cacheMode)
    {
        return null;
    }

    /**
     * @return string
     */
    abstract public function getSQL();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return null;
    }

    /**
     * @return null
     */
    public function free()
    {
        return null;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getParameters()
    {
        return null;
    }

    /**
     * @param mixed $key
     * @return \Doctrine\ORM\Query\Parameter|null
     */
    public function getParameter($key)
    {
        return null;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|array $parameters
     * @return self
     */
    public function setParameters($parameters)
    {
        return $this;
    }

    /**
     * @param integer $firstResult
     * @return self
     */
    public function setFirstResult($firstResult)
    {
        return $this;
    }

    /**
     * @param integer $maxResults
     * @return self
     */
    public function setMaxResults($maxResults)
    {
        return $this;
    }

    /**
     * @param string|int $key
     * @param mixed $value
     * @param string|null $type
     * @return self
     */
    public function setParameter($key, $value, $type = null)
    {
        return null;
    }

    /**
     * @param mixed $value
     * @return array
     */
    public function processParameterValue($value)
    {
        return null;
    }

    /**
     * @param ResultSetMapping $rsm
     * @return self
     */
    public function setResultSetMapping(ResultSetMapping $rsm)
    {
        return null;
    }

    /**
     * @param QueryCacheProfile $profile
     * @return self
     */
    public function setHydrationCacheProfile(QueryCacheProfile $profile = null)
    {
        return null;
    }

    /**
     * @return QueryCacheProfile
     */
    public function getHydrationCacheProfile()
    {
        return null;
    }

    /**
     * @param QueryCacheProfile $profile
     * @return self
     */
    public function setResultCacheProfile(QueryCacheProfile $profile = null)
    {
        return null;
    }

    /**
     * @param \Doctrine\Common\Cache\Cache|null $resultCacheDriver
     * @return self
     */
    public function setResultCacheDriver($resultCacheDriver = null)
    {
        return null;
    }

    /**
     * @return \Doctrine\Common\Cache\Cache Cache driver
     */
    public function getResultCacheDriver()
    {
        return null;
    }

    /**
     * @param boolean $bool
     * @param integer $lifetime
     * @param string  $resultCacheId
     * @return self
     */
    public function useResultCache($bool, $lifetime = null, $resultCacheId = null)
    {
        return null;
    }

    /**
     * @param integer $lifetime
     * @return self
     */
    public function setResultCacheLifetime($lifetime)
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getResultCacheLifetime()
    {
        return null;
    }

    /**
     * @param boolean $expire
     * @return self
     */
    public function expireResultCache($expire = true)
    {
        return null;
    }

    /**
     * @return boolean
     */
    public function getExpireResultCache()
    {
        return null;
    }

    /**
     * @return QueryCacheProfile
     */
    public function getQueryCacheProfile()
    {
        return null;
    }

    /**
     * @param string $class
     * @param string $assocName
     * @param int $fetchMode
     * @return self
     */
    public function setFetchMode($class, $assocName, $fetchMode)
    {
        return null;
    }

    /**
     * @param integer $hydrationMode
     * @return self
     */
    public function setHydrationMode($hydrationMode)
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getHydrationMode()
    {
        return null;
    }

    /**
     * @param int $hydrationMode
     * @return array
     */
    public function getResult($hydrationMode = self::HYDRATE_OBJECT)
    {
        return null;
    }

    /**
     * @return array
     */
    public function getArrayResult()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getScalarResult()
    {
        return null;
    }

    /**
     * @param int $hydrationMode
     * @return mixed
     */
    public function getOneOrNullResult($hydrationMode = null)
    {
        return null;
    }

    /**
     * @param integer $hydrationMode
     * @return mixed
     */
    public function getSingleResult($hydrationMode = null)
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getSingleScalarResult()
    {
        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function setHint($name, $value)
    {
        return null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getHint($name)
    {
        return null;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function hasHint($name)
    {
        return null;
    }

    /**
     * @return array
     */
    public function getHints()
    {
        return null;
    }

    /**
     * @param ArrayCollection|array|null $parameters
     * @param integer $hydrationMode
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    public function iterate($parameters = null, $hydrationMode = self::HYDRATE_OBJECT)
    {
        return null;
    }

    /**
     * Executes the query.
     *
     * @param ArrayCollection|array|null
     * @param integer|null $hydrationMode
     * @return mixed
     */
    public function execute($parameters = null, $hydrationMode = null)
    {
        return null;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setResultCacheId($id)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getResultCacheId()
    {
        return null;
    }
}
