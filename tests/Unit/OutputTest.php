<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mgid\Component\Pagination\Output;

final class OutputTest extends TestCase
{
    public function testOutput(): void
    {
        $count = 123;
        $items = ['foo', 'bar', 'baz'];

        $output = new Output($count, $items);

        $this->assertSame($count, $output->getCount());
        $this->assertSame($items, $output->getItems());
    }
}
