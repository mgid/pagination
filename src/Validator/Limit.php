<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Limit extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'validation.pagination.limit.invalid';

    /**
     * @var int
     */
    public int $min = 0;

    /**
     * @var int
     */
    public int $max = \PHP_INT_MAX;

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
