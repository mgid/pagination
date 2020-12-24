<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

final class Output implements OutputInterface
{
    /**
     * @var int
     */
    private int $count;

    /**
     * @var iterable<array>
     */
    private iterable $items;

    /**
     * @param int             $count
     * @param iterable<array> $items
     */
    public function __construct(int $count, iterable $items)
    {
        $this->count = $count;
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): iterable
    {
        return $this->items;
    }
}
