<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mgid\Component\Pagination\InputInterface;

final class FieldNameValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($input, Constraint $constraint): void
    {
        if (!$constraint instanceof FieldName) {
            throw new Exception\UnexpectedTypeException($constraint, FieldName::class); // @codeCoverageIgnore
        }

        if (!$input instanceof InputInterface) {
            throw new Exception\UnexpectedTypeException($input, InputInterface::class); // @codeCoverageIgnore
        }

        foreach ($this->getFields($input) as $fieldName) {
            if (!\preg_match($constraint->pattern, $fieldName)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath($fieldName)
                    ->setParameter('{{ field }}', $fieldName)
                    ->addViolation();
            }
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    private function getFields(InputInterface $input): array
    {
        return \array_merge(
            \array_keys($input->getOrders()),
            \array_keys($input->getFilters())
        );
    }
}
