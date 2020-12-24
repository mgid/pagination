<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mgid\Component\Pagination\Input;
use Mgid\Component\Pagination\Contract\FilterableInterface;

final class InputTest extends TestCase
{
    public function testInput(): void
    {
        $limit = 25;
        $offset = 50;
        $orders = ['age' => 'desc', 'name' => 'asc'];
        $filters = ['birthday' => [FilterableInterface::GREATER_THEN => '21']];

        $input = new Input($limit, $offset, $orders, $filters);

        $this->assertSame($limit, $input->getLimit());
        $this->assertSame($offset, $input->getOffset());
        $this->assertSame($orders, $input->getOrders());
        $this->assertSame($filters, $input->getFilters());
    }
}
