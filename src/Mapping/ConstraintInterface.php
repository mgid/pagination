<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Mapping;

interface ConstraintInterface
{
    /**
     * @param string[] $orders
     */
    public function setOrders(array $orders): void;

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isAllowedOrder(string $fieldName): bool;

    /**
     * @param string[] $filters
     */
    public function setFilters(array $filters): void;

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    public function isAllowedFilter(string $fieldName): bool;
}
