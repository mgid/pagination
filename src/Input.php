<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

use Mgid\Component\Pagination\Validator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\Sequentially({
 *     @Validator\Structure(),
 *     @Validator\Limit(),
 *     @Validator\Offset(),
 *     @Validator\FieldName(),
 *     @Validator\Operator(),
 *     @Validator\Order(),
 * })
 */
class Input implements InputInterface
{
    /**
     * @var int
     */
    private int $limit;

    /**
     * @var int
     */
    private int $offset;

    /**
     * @var array<string,string>
     */
    private array $orders;

    /**
     * @var array<string,array<string,string>>
     */
    private array $filters;

    /**
     * @param int                                $limit
     * @param int                                $offset
     * @param array<string,string>               $orders
     * @param array<string,array<string,string>> $filters
     */
    public function __construct(int $limit = 20, int $offset = 0, array $orders = [], array $filters = [])
    {
        $this->setLimit($limit);
        $this->setOffset($offset);
        $this->setOrders($orders);
        $this->setFilters($filters);
    }

    /**
     * {@inheritdoc}
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

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
    public function getOrders(): array
    {
        return $this->orders;
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
    public function getFilters(): array
    {
        return $this->filters;
    }
}
