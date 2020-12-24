<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Adapter;

use Mgid\Component\Pagination\OutputInterface;
use Mgid\Component\Pagination\Contract\SortableInterface;
use Mgid\Component\Pagination\Contract\FilterableInterface;
use Mgid\Component\Pagination\Contract\PaginationInterface;

interface QueryBuilderAdapterInterface extends OutputInterface
{
    /**
     * @return string|null
     */
    public function getRootAlias(): ?string;

    /**
     * @param SortableInterface $input
     */
    public function addOrders(SortableInterface $input): void;

    /**
     * @param FilterableInterface $input
     */
    public function addFilters(FilterableInterface $input): void;

    /**
     * @param PaginationInterface $input
     */
    public function addPagination(PaginationInterface $input): void;
}
