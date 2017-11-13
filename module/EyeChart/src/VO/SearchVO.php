<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/15/2017
 * (c) 2017
 */

namespace EyeChart\VO;

use Assert\Assertion;

/**
 * Class SearchVO
 * @package EyeChart\VO
 */
final class SearchVO extends AbstractVO implements VOSearchInterface
{
    /** @var int */
    protected $limit;

    /** @var array[] */
    protected $order;

    /** @var string */
    protected $search;

    /** @var string */
    protected $searchCapitalized;

    /** @var int */
    protected $offset;

    /**
     * SearchVO constructor.
     * @param int $limit
     * @param array[] $order
     * @param string $search
     * @param int $offset
     */
    public function __construct($limit, $order, $search, $offset)
    {
        $this->setLimit($limit);
        $this->setOrder($order);
        $this->setSearch($search);
        $this->setOffset($offset);
    }

    /**
     * @return int
     */
    public function getLimit():? int
    {
        return $this->limit;
    }

    /**
     * @return array
     */
    public function getOrder():? array
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getSearch():? string
    {
        return $this->search;
    }

    /**
     * @return string
     */
    public function getSearchCapitalized():? string
    {
        return $this->searchCapitalized;
    }

    /**
     * @return int
     */
    public function getOffset():? int
    {
        return $this->offset;
    }

    /**
     * @param int $limit
     */
    private function setLimit($limit): void
    {
        if (! is_null($limit)) {
            Assertion::integer($limit, "Limit must be an integer");
            Assertion::greaterOrEqualThan($limit, 0, "Limit must be no less than 0");

            $this->limit = $limit;
        }
    }

    /**
     * @param array $order
     */
    private function setOrder($order): void
    {
        if (! is_null($order)) {
            Assertion::isArray($order, "Order must be an array");

            foreach ($order as $value) {
                Assertion::isObject($value, "Data within order must be an object");
                Assertion::propertiesExist($value, ['column', 'sort']);
            }

            $this->order = $order;
        }
    }

    /**
     * @param string $search
     */
    private function setSearch($search): void
    {
        if (! is_null($search)) {
            Assertion::string($search, "Search value must be a string");

            $this->search = $search;

            $this->capitalizeSearch();
        }
    }

    private function capitalizeSearch(): void
    {
        if (! is_null($this->search)) {
            Assertion::string($this->getSearch(), "Search value must be a string");

            $this->searchCapitalized = strtoupper($this->getSearch());
        }
    }

    /**
     * @param int $offset
     */
    private function setOffset($offset): void
    {
        if (! is_null($offset)) {
            Assertion::integer($offset, "Offset must be an integer");
            Assertion::greaterOrEqualThan($offset, 0, "Offset must be 0 or greater");

            $this->offset = $offset;
        }
    }
}
