<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Contract;

interface SortableInterface
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    public const ORDERS = [
        self::ASC,
        self::DESC,
    ];

    /**
     * @param array<string,string> $orders
     */
    public function setOrders(array $orders): void;

    /**
     * Example:
     * <code>
     *  [
     *      'name' => 'asc',
     *      'price' => 'desc',
     *  ]
     * </code>
     *
     * @return array<string,string>
     */
    public function getOrders(): array;
}
