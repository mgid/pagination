<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Contract;

interface PaginationInterface
{
    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void;

    /**
     * @return int
     */
    public function getOffset(): int;
}
