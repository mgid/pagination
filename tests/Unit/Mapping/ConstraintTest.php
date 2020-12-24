<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Mgid\Component\Pagination\Mapping\Constraint;

final class ConstraintTest extends TestCase
{
    public function testAllowedFields(): void
    {
        $constraint = new Constraint();
        $constraint->setOrders(['name']);
        $constraint->setFilters(['age']);

        $this->assertTrue($constraint->isAllowedOrder('name'));
        $this->assertTrue($constraint->isAllowedFilter('age'));

        $this->assertFalse($constraint->isAllowedOrder('foo'));
        $this->assertFalse($constraint->isAllowedFilter('baz'));
    }
}
