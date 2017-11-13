<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 10/23/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures\Doctrine;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class DoctrineSetup
 * @package Doctrine
 *
 * This fixture provides all of the necessary prerequisites to satisfy mocking doctrine.  Please note though that new
 * predicate types may need to be added as our testing framework expands.
 */
final class DoctrineSetup extends TestCase
{
    /** @var string string */
    private $subjectEntity;

    /** @var string string */
    private $subjectAlias;

    /** @var  EntityRepository|PHPUnit_Framework_MockObject_MockObject */
    private $mockedRepository;

    /** @var QueryBuilder|PHPUnit_Framework_MockObject_MockObject */
    private $mockedQueryBuilder;

    /** @var EntityManager|PHPUnit_Framework_MockObject_MockObject */
    private $mockedEntityManager;

    /** @var Query|PHPUnit_Framework_MockObject_MockObject */
    private $mockedQuery;

    /** @var AbstractQuery|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAbstractQuery;

    /**
     * DoctrineSetup constructor.
     * @param string $subjectEntity
     * @param string $subjectAlias
     */
    public function __construct(string $subjectEntity, string $subjectAlias)
    {
        parent::__construct();

        $this->subjectEntity = $subjectEntity;
        $this->subjectAlias  = $subjectAlias;

        // Query::class is also a fixture (located in this namespace) that allows for mocking due to the actual doctrine
        // \Doctrine\ORM\Query class being marked private
        $this->mockedQuery = $this->getMockBuilder(Query::class)->getMock();

        $this->queryBuilderMockingSetup();
        $this->repositoryMockingSetup();
        $this->abstractQueryMockingSetup();
        $this->entityManagerMockingSetup();
    }

    /**
     * @return Query|PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockedQuery(): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->mockedQuery;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockedAbstractQuery(): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->mockedAbstractQuery;
    }

    /**
     * @return EntityManager|PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockedEntityManager(): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->mockedEntityManager;
    }

    private function queryBuilderMockingSetup(): void
    {
        $this->mockedQueryBuilder = $this->getMockBuilder(QueryBuilder::class)
                                         ->disableOriginalConstructor()
                                         ->getMock();

        $this->mockedQueryBuilder->expects($this->any())
                                 ->method('where')
                                 ->willReturn($this->mockedQueryBuilder);

        $this->mockedQueryBuilder->expects($this->any())
                                 ->method('andWhere')
                                 ->willReturn($this->mockedQueryBuilder);

        $this->mockedQueryBuilder->expects($this->any())
                                 ->method('setParameter')
                                 ->willReturn($this->mockedQueryBuilder);

        $this->mockedQueryBuilder->expects($this->any())
                                 ->method('getQuery')
                                 ->willReturn($this->mockedQuery);

        $this->mockedQueryBuilder->expects($this->any())
                                  ->method('expr')
                                  ->willReturn(new Expr());

        $this->mockedQueryBuilder->expects($this->any())
                                 ->method('setMaxResults')
                                 ->willReturn($this->mockedQueryBuilder);
    }

    private function repositoryMockingSetup(): void
    {
        $this->mockedRepository = $this->getMockBuilder(EntityRepository::class)
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->mockedRepository->expects($this->any())
                               ->method('createQueryBuilder')
                               ->with($this->subjectAlias)
                               ->willReturn($this->mockedQueryBuilder);
    }

    private function entityManagerMockingSetup(): void
    {
        $this->mockedEntityManager = $this->getMockBuilder(EntityManager::class)
                                           ->disableOriginalConstructor()
                                           ->getMock();

        $this->mockedEntityManager->expects($this->any())
                                  ->method('getRepository')
                                  ->with($this->subjectEntity)
                                  ->willReturn($this->mockedRepository);

        $this->mockedEntityManager->expects($this->any())
                                  ->method('createNativeQuery')
                                  ->withAnyParameters()
                                  ->willReturn($this->mockedAbstractQuery);
    }

    private function abstractQueryMockingSetup(): void
    {
        $this->mockedAbstractQuery = $this->getMockBuilder(AbstractQuery::class)
                                          ->disableOriginalConstructor()
                                          ->getMock();
    }
}
