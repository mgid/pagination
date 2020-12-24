<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Adapter\Doctrine\ODM;

use Doctrine\ODM\MongoDB\Aggregation\Builder;
use Mgid\Component\Pagination\Contract\SortableInterface;
use Mgid\Component\Pagination\Contract\PaginationInterface;
use Mgid\Component\Pagination\Contract\FilterableInterface;

final class AggregationBuilderAdapter extends AbstractQueryBuilderAdapter
{
    private const OPTIONS = [
        'allowDiskUse' => true,
    ];

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
        $match = $this->builder->match();

        foreach ($filterable->getFilters() as $fieldName => $operators) {
            foreach ($operators as $operator => $value) {
                $match->addAnd(
                    $match->expr()->field($fieldName)->operator($this->getOperator($operator), $value)
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
     */
    public function getCount(): int
    {
        return (int) ((clone $this->builder)->count('count')->execute(self::OPTIONS)->current()['count'] ?? 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): iterable
    {
        return $this->builder->execute(self::OPTIONS);
    }
}
