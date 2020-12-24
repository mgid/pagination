<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Adapter\Doctrine\ODM;

use Doctrine\ODM\MongoDB\Query\Builder;
use Mgid\Component\Pagination\Contract\SortableInterface;
use Mgid\Component\Pagination\Contract\PaginationInterface;
use Mgid\Component\Pagination\Contract\FilterableInterface;

final class QueryBuilderAdapter extends AbstractQueryBuilderAdapter
{
    /**
     * @var Builder
     */
    private Builder $builder;

    /**
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootAlias(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrders(SortableInterface $sortable): void
    {
        foreach ($sortable->getOrders() as $fieldName => $order) {
            $this->builder->sort($fieldName, $order);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilters(FilterableInterface $filterable): void
    {
        foreach ($filterable->getFilters() as $fieldName => $operators) {
            foreach ($operators as $operator => $value) {
                $this->builder->addAnd(
                    $this->builder->expr()->field($fieldName)->operator($this->getOperator($operator), $value)
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addPagination(PaginationInterface $pagination): void
    {
        if ($pagination->getOffset() > 0) {
            $this->builder->skip($pagination->getOffset());
        }

        if ($pagination->getLimit() > 0) {
            $this->builder->limit($pagination->getLimit());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getCount(): int
    {
        $count = (clone $this->builder)->count()->getQuery()->execute();

        if (\is_int($count)) {
            return $count;
        }

        return 0; // @codeCoverageIgnore
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getItems(): iterable
    {
        return $this->builder->getQuery()->getIterator();
    }
}
