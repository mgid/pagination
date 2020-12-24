<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

interface OutputInterface
{
    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return iterable<array>
     */
    public function getItems(): iterable;
}
