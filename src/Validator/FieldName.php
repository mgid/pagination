<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class FieldName extends Constraint
{
    public const ERROR_MESSAGE = 'validation.pagination.fieldName.invalid';

    /**
     * @var string
     */
    public string $message = self::ERROR_MESSAGE;

    /**
     * @var string
     */
    public string $pattern = '#^[a-z0-9_\.]+$#i';

    /**
     * @return string[]
     */
    public function getTargets(): array
    {
        return [self::CLASS_CONSTRAINT];
    }
}
