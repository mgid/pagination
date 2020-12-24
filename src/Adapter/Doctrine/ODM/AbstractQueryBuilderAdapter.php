<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Adapter\Doctrine\ODM;

use Mgid\Component\Pagination\Contract\FilterableInterface;
use Mgid\Component\Pagination\Adapter\QueryBuilderAdapterInterface;

abstract class AbstractQueryBuilderAdapter implements QueryBuilderAdapterInterface
{
    private const OPERATORS = [
        FilterableInterface::EQUAL => '$eq',
        FilterableInterface::NOT_EQUAL => '$ne',
        FilterableInterface::IN => '$in',
        FilterableInterface::NOT_IN => '$nin',
        FilterableInterface::LESS_THEN => '$lt',
        FilterableInterface::LESS_THEN_OR_EQUAL => '$lte',
        FilterableInterface::GREATER_THEN => '$gt',
        FilterableInterface::GREATER_THEN_OR_EQUAL => '$gte',
        FilterableInterface::LIKE => '$regex',
    ];

    /**
     * @param string $alias
     *
     * @return string
     */
    protected function getOperator(string $alias): string
    {
        return self::OPERATORS[$alias];
    }
}
