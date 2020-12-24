<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit\Adapter\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\MockObject\MockObject;
use Mgid\Component\Pagination\Input;
use Mgid\Component\Pagination\Adapter;
use Mgid\Component\Pagination\Paginator;
use Mgid\Component\Pagination\PaginatorInterface;

final class QueryBuilderAdapterTest extends TestCase
{
    public function testPaginateWithEmptyResult()
    {
        $em = $this->getEntityManagerMock();
        $queryBuilder = $this->getQueryBuilderMock($em);

        $query = $this
            ->getMockBuilder(AbstractQuery::class)
            ->setConstructorArgs([$em])
            ->getMockForAbstractClass();

        $queryBuilder->expects($this->once())->method('getQuery')->willReturn($query);

        $output = $this->getPaginator()->paginate($queryBuilder, new Input());

        $this->assertSame(0, $output->getCount());
        $this->assertSame([], $output->getItems());
    }

    public function testPaginateWithResult()
    {
        $count = 123;
        $items = ['foo', 'bar', 'baz'];

        $em = $this->getEntityManagerMock();
        $queryBuilder = $this->getQueryBuilderMock($em);

        $query = $this
            ->getMockBuilder(AbstractQuery::class)
            ->setConstructorArgs([$em])
            ->getMock();

        $query->expects($this->once())->method('getSingleScalarResult')->willReturn($count);
        $query->expects($this->once())->method('getArrayResult')->willReturn($items);

        $queryBuilder->expects($this->any())->method('getQuery')->willReturn($query);

        $input = new Input(10, 20, ['name' => 'asc'], ['age' => ['eq' => 21]]);
        $output = $this->getPaginator()->paginate($queryBuilder, $input);

        $this->assertSame($count, $output->getCount());
        $this->assertSame($items, $output->getItems());
    }

    /**
     * @return PaginatorInterface
     */
    private function getPaginator(): PaginatorInterface
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $paginator = new Paginator($validator);

        $paginator->addAdapter(QueryBuilder::class, Adapter\Doctrine\ORM\QueryBuilderAdapter::class);

        return $paginator;
    }

    /**
     * @return MockObject
     */
    private function getEntityManagerMock(): MockObject
    {
        $connection = $this
            ->getMockBuilder(\Doctrine\DBAL\Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configuration = $this
            ->getMockBuilder(\Doctrine\ORM\Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $configuration->expects($this->any())->method('getDefaultQueryHints')->willReturn([]);

        $em = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->any())->method('getConnection')->willReturn($connection);
        $em->expects($this->any())->method('getConfiguration')->willReturn($configuration);

        $hydrator = $this
            ->getMockBuilder(\Doctrine\ORM\Internal\Hydration\AbstractHydrator::class)
            ->setConstructorArgs([$em])
            ->getMock();

        $em->expects($this->any())->method('newHydrator')->willReturn($hydrator);

        return $em;
    }

    /**
     * @param MockObject $em
     *
     * @return MockObject
     */
    private function getQueryBuilderMock(MockObject $em): MockObject
    {
        $queryBuilder = $this
            ->getMockBuilder(QueryBuilder::class)
            ->setConstructorArgs([$em])
            ->getMock();

        $expression = $this
            ->getMockBuilder(\Doctrine\ORM\Query\Expr::class)
            ->getMock();

        $queryBuilder->expects($this->any())->method('expr')->willReturn($expression);
        $queryBuilder->expects($this->once())->method('select')->with('COUNT(1)')->willReturnSelf();

        return $queryBuilder;
    }
}
