<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Mapping;

final class Constraint implements ConstraintInterface
{
    /**
     * @var string[]
     */
    private array $orders = [];

    /**
     * @var string[]
     */
    private array $filters = [];

    /**
     * {@inheritdoc}
     */
    public function setOrders(array $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowedOrder(string $fieldName): bool
    {
        return empty($this->orders) || \in_array($fieldName, $this->orders, true);
    }

    /**
     * {@inheritdoc}
     */
    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowedFilter(string $fieldName): bool
    {
        return empty($this->filters) || \in_array($fieldName, $this->filters, true);
    }
}
