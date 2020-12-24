<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mgid\Component\Pagination\InputInterface;

final class StructureValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($input, Constraint $constraint): void
    {
        if (!$input instanceof InputInterface) {
            throw new Exception\UnexpectedTypeException($input, InputInterface::class); // @codeCoverageIgnore
        }

        if (!$constraint instanceof Structure) {
            throw new Exception\UnexpectedTypeException($constraint, Structure::class); // @codeCoverageIgnore
        }

        $this->validateOrders($input->getOrders(), $constraint);
        $this->validateFilters($input->getFilters(), $constraint);
    }

    /**
     * @param array<mixed> $orders
     * @param Structure    $constraint
     */
    private function validateOrders(array $orders, Structure $constraint): void
    {
        foreach ($orders as $fieldName => $order) {
            if (!\is_string($fieldName) || !\is_string($order)) {
                $this->context->buildViolation($constraint->message)->atPath('orders')->addViolation();

                break;
            }
        }
    }

    /**
     * @param array<mixed> $filters
     * @param Structure    $constraint
     */
    private function validateFilters(array $filters, Structure $constraint): void
    {
        foreach ($filters as $fieldName => $operators) {
            if (!\is_string($fieldName) || !\is_array($operators)) {
                $this->context->buildViolation($constraint->message)->atPath('filters')->addViolation();

                return;
            }

            foreach ($operators as $operator => $value) {
                if (!\is_string($operator)) {
                    $this->context->buildViolation($constraint->message)->atPath('filters.operators')->addViolation();

                    return;
                }
            }
        }
    }
}
