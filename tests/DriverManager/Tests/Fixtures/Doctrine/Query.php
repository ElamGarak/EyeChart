<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 10/23/2017
 * (c) null 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query as DoctrineQuery;

/**
 * Class Query
 * @package Fixtures
 *
 * This is meant to act as a stand in to mock \Doctrine\ORM\Query which happens to be final.  Since final classes are
 * unmockable, this can be mocked instead and make full use of the the methods that are available within
 * \Doctrine\ORM\Query as of doctrine 2.0.
 *
 * This fixture (just as in the case of the actual doctrine class, extends an
 * abstract class which continues the initialization of the same methods with some deviation.  The type annotations have
 * been retained for convenience.
 *
 * Note on return type signatures:  PHPUnit states: strict_types declaration must not use block mode
 */
class Query extends AbstractQuery
{
    const STATE_CLEAN               = DoctrineQuery::STATE_CLEAN;
    const STATE_DIRTY               = DoctrineQuery::STATE_DIRTY;
    const HINT_REFRESH              = DoctrineQuery::HINT_REFRESH;
    const HINT_CACHE_ENABLED        = DoctrineQuery::HINT_CACHE_ENABLED;
    const HINT_CACHE_EVICT          = DoctrineQuery::HINT_CACHE_EVICT;
    const HINT_REFRESH_ENTITY       = DoctrineQuery::HINT_REFRESH_ENTITY;
    const HINT_FORCE_PARTIAL_LOAD   = DoctrineQuery::HINT_FORCE_PARTIAL_LOAD;
    const HINT_INCLUDE_META_COLUMNS = DoctrineQuery::HINT_INCLUDE_META_COLUMNS;
    const HINT_CUSTOM_TREE_WALKERS  = DoctrineQuery::HINT_CUSTOM_TREE_WALKERS;
    const HINT_CUSTOM_OUTPUT_WALKER = DoctrineQuery::HINT_CUSTOM_OUTPUT_WALKER;
    const HINT_INTERNAL_ITERATION   = DoctrineQuery::HINT_INTERNAL_ITERATION;
    const HINT_LOCK_MODE            = DoctrineQuery::HINT_LOCK_MODE;


    public function getSQL()
    {
        return null;
    }

    /**
     * @return \Doctrine\ORM\Query\AST\SelectStatement |
     *         \Doctrine\ORM\Query\AST\UpdateStatement |
     *         \Doctrine\ORM\Query\AST\DeleteStatement
     */
    public function getAST()
    {
        return null;
    }

    /**
     * @param \Doctrine\Common\Cache\Cache|null $queryCache
     * @return Query
     */
    public function setQueryCacheDriver($queryCache)
    {
        return null;
    }

    /**
     * @param boolean $bool
     * @return Query
     */
    public function useQueryCache($bool)
    {
        return null;
    }

    /**
     * @return \Doctrine\Common\Cache\Cache|null
     */
    public function getQueryCacheDriver()
    {
        return null;
    }

    /**
     * @param integer $timeToLive
     * @return Query
     */
    public function setQueryCacheLifetime($timeToLive)
    {
        return null;
    }

    /**
     * @return int
     */
    public function getQueryCacheLifetime()
    {
        return null;
    }

    /**
     * @param boolean $expire
     * @return Query
     */
    public function expireQueryCache($expire = true)
    {
        return null;
    }

    /**
     * @return bool
     */
    public function getExpireQueryCache()
    {
        return null;
    }

    public function free()
    {
        return null;
    }

    /**
     * @param string $dqlQuery
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function setDQL($dqlQuery)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getDQL()
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getState()
    {
        return null;
    }

    /**
     * @param $dql
     * @return boolean
     */
    public function contains($dql)
    {
        return null;
    }

    /**
     * @return integer
     */
    public function getFirstResult()
    {
        return null;
    }

    /**
     * @param integer $firstResult
     * @return Query|AbstractQuery
     */
    public function setFirstResult($firstResult)
    {
        return parent::setFirstResult($firstResult);
    }

    /**
     * @param integer $maxResults
     * @return Query|AbstractQuery
     */
    public function setMaxResults($maxResults)
    {
        return parent::setMaxResults($maxResults);
    }

    /**
     * @return integer
     */
    public function getMaxResults()
    {
        return null;
    }

    /**
     * @param ArrayCollection|array|null $parameters
     * @param integer $hydrationMode
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    public function iterate($parameters = null, $hydrationMode = null)
    {
        return parent::iterate($parameters, $hydrationMode);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return Query|AbstractQuery
     */
    public function setHint($name, $value)
    {
        return parent::setHint($name, $value);
    }

    /**
     * @param integer $hydrationMode
     * @return Query|AbstractQuery
     */
    public function setHydrationMode($hydrationMode)
    {
        return parent::setHydrationMode($hydrationMode);
    }

    /**
     * @param int $lockMode
     * @return Query
     */
    public function setLockMode($lockMode)
    {
        return null;
    }

    /**
     * @return int|null
     */
    public function getLockMode()
    {
        return null;
    }
}
