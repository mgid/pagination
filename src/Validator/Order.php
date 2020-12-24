<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Constraint;
use Mgid\Component\Pagination\Contract\SortableInterface;

/**
 * @Annotation
 */
final class Order extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'validation.pagination.order.invalid';

    /**
     * @var string[]
     */
    public array $orders = SortableInterface::ORDERS;

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
