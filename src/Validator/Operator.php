<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Constraint;
use Mgid\Component\Pagination\Contract\FilterableInterface;

/**
 * @Annotation
 */
final class Operator extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'validation.pagination.operator.invalid';

    /**
     * @var string[]
     */
    public array $operators = FilterableInterface::OPERATORS;

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
