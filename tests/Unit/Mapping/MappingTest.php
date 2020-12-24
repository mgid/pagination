<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Mgid\Component\Pagination\Input;
use Mgid\Component\Pagination\Mapping\Mapping;
use Mgid\Component\Pagination\Normalizer\ChainNormalizer;
use Mgid\Component\Pagination\Mapping\ConstraintInterface;

final class MappingTest extends TestCase
{
    public function testEmptyRequest(): void
    {
        $input = new Input();
        $mapping = new Mapping();

        $mapping->normalize($input, new ChainNormalizer([]));

        $this->assertSame([], $input->getOrders());
        $this->assertSame([], $input->getFilters());
    }

    public function testAssociationsWithRootAlias(): void
    {
        $input = new Input(0, 0, ['name' => 'asc', 'age' => 'desc']);
        $mapping = new Mapping();
        $mapping->setDelimiter('-');
        $mapping->setRootAlias('table');
        $mapping->setAssociations(['name' => 'table2.fullName']);

        $mapping->normalize($input, new ChainNormalizer([]));

        $this->assertSame(['table2.fullName' => 'asc', 'table-age' => 'desc'], $input->getOrders());
        $this->assertSame([], $input->getFilters());
    }

    public function testConstraint(): void
    {
        $mapping = new Mapping();

        $this->assertInstanceOf(ConstraintInterface::class, $mapping->getConstraint());
    }
}
