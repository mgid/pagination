<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

use Mgid\Component\Pagination\Contract\SortableInterface;
use Mgid\Component\Pagination\Contract\PaginationInterface;
use Mgid\Component\Pagination\Contract\FilterableInterface;

interface InputInterface extends PaginationInterface, SortableInterface, FilterableInterface
{
}
