<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mgid\Component\Pagination\InputInterface;

final class OperatorValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($input, Constraint $constraint): void
    {
        if (!$constraint instanceof Operator) {
            throw new Exception\UnexpectedTypeException($constraint, Operator::class); // @codeCoverageIgnore
        }

        if (!$input instanceof InputInterface) {
            throw new Exception\UnexpectedTypeException($input, InputInterface::class); // @codeCoverageIgnore
        }

        foreach ($this->getOperators($input) as $fieldName => $operator) {
            if (false === \in_array($operator, $constraint->operators, true)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath($fieldName)
                    ->setParameter('{{ operator }}', $operator)
                    ->addViolation();
            }
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return \Generator|string[]
     */
    private function getOperators(InputInterface $input): \Generator
    {
        foreach ($input->getFilters() as $fieldName => $operators) {
            foreach (\array_keys($operators) as $operator) {
                yield $fieldName => $operator;
            }
        }
    }
}
