<?php
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/28/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\VO;

use EyeChart\VO\SearchVO;
use stdClass;

/**
 * Class SearchVOTest
 * @package EyeChart\Tests\VO
 */
class SearchVOTest extends VOTest
{
    /** @var string */
    protected static $subjectVONameSpace = SearchVO::class;

    /** @var array[] */
    protected static $validAssertionValues = [
        'limit'  => [1],
        'order'  => [],
        'search' => ['foo'],
        'offset' => [1]
    ];

    /** @var array[]  */
    protected static $invalidAssertionValues = [
        'limit'  => ['a', 0],
        'order'  => [],
        'search' => [1],
        'offset' => ['b', 0]
    ];

    public static function setUpBeforeClass(): void
    {
        self::$validAssertionValues['order'] = [
            [
                (object) [
                    'column' => 'foo',
                    'sort'   => 'bar'
                ]
            ]
        ];

        self::$invalidAssertionValues['order'][] = new stdClass();
        self::$invalidAssertionValues['order'][] = [];
        self::$invalidAssertionValues['order'][] = (object) ['foo'    => 'bar'];
        self::$invalidAssertionValues['order'][] = (object) ['column' => 'bar'];
        self::$invalidAssertionValues['order'][] = (object) ['sort'   => 'bar'];

        parent::$subjectVONameSpace     = self::$subjectVONameSpace;
        parent::$validAssertionValues   = self::$validAssertionValues;
        parent::$invalidAssertionValues = self::$invalidAssertionValues;

        parent::setUpBeforeClass();
    }

    public function testDependencyTypeHintWasSet(): void
    {
        $this->assertNull(null, "No type hints have been set for this VO");
    }

    public function testGetSearchCapitalizedReturnsAllCapsOfValue(): void
    {
        $value = 'foo';

        $vo = SearchVO::build()->setSearch($value);

        $this->assertEquals(strtoupper($value), $vo->getSearchCapitalized());
    }

    /**
     * For Optional Return Types
     */
    public function testGettersReturnNulls(): void
    {
        // Test when each setter is passed null
        $vo = SearchVO::build()->setSearch(null)
                               ->setOffset(null)
                               ->setOrder(null)
                               ->setLimit(null);

        $this->assertNull($vo->getSearch());
        $this->assertNull($vo->getSearchCapitalized());
        $this->assertNull($vo->getLimit());
        $this->assertNull($vo->getOffset());
        $this->assertNull($vo->getOrder());

        // Test default values
        $vo = new SearchVO();

        $this->assertNull($vo->getSearch());
        $this->assertNull($vo->getSearchCapitalized());
        $this->assertNull($vo->getLimit());
        $this->assertNull($vo->getOffset());
        $this->assertNull($vo->getOrder());
    }
}
