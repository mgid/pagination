<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Structure extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'validation.pagination.structure.invalid';

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
