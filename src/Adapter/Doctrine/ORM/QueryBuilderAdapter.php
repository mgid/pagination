<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Adapter\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Mgid\Component\Pagination\Contract\SortableInterface;
use Mgid\Component\Pagination\Contract\PaginationInterface;
use Mgid\Component\Pagination\Contract\FilterableInterface;
use Mgid\Component\Pagination\Adapter\QueryBuilderAdapterInterface;

final class QueryBuilderAdapter implements QueryBuilderAdapterInterface
{
    private const OPERATORS = [
        FilterableInterface::EQUAL => 'eq',
        FilterableInterface::NOT_EQUAL => 'neq',
        FilterableInterface::IN => 'in',
        FilterableInterface::NOT_IN => 'notIn',
        FilterableInterface::LESS_THEN => 'lt',
        FilterableInterface::LESS_THEN_OR_EQUAL => 'lte',
        FilterableInterface::GREATER_THEN => 'gt',
        FilterableInterface::GREATER_THEN_OR_EQUAL => 'gte',
        FilterableInterface::LIKE => 'like',
    ];

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $builder;

    /**
     * @param QueryBuilder $builder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootAlias(): ?string
    {
        return $this->builder->getRootAliases()[0] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrders(SortableInterface $sortable): void
    {
        foreach ($sortable->getOrders() as $fieldName => $order) {
            $this->builder->addOrderBy($fieldName, $order);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilters(FilterableInterface $filterable): void
    {
        $i = 0;
        $expr = $this->builder->expr();

        foreach ($filterable->getFilters() as $fieldName => $operators) {
            foreach ($operators as $operator => $value) {
                $this->builder->setParameter(++$i, $value);
                $this->builder->andWhere($expr->{$this->getOperator($operator)}($fieldName, \sprintf('?%d', $i)));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addPagination(PaginationInterface $pagination): void
    {
        if ($pagination->getOffset() > 0) {
            $this->builder->setFirstResult($pagination->getOffset());
        }

        if ($pagination->getLimit() > 0) {
            $this->builder->setMaxResults($pagination->getLimit());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCount(): int
    {
        return (int) (clone $this->builder)->select('COUNT(1)')->getQuery()->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): iterable
    {
        return $this->builder->getQuery()->getArrayResult();
    }

    /**
     * @param string $alias
     *
     * @return string
     */
    private function getOperator(string $alias): string
    {
        return self::OPERATORS[$alias];
    }
}
