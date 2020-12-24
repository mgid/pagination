<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mgid\Component\Pagination\InputInterface;

final class OffsetValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($input, Constraint $constraint): void
    {
        if (!$constraint instanceof Offset) {
            throw new Exception\UnexpectedTypeException($constraint, Offset::class); // @codeCoverageIgnore
        }

        if (!$input instanceof InputInterface) {
            throw new Exception\UnexpectedTypeException($input, InputInterface::class); // @codeCoverageIgnore
        }

        if ($input->getOffset() >= $constraint->min && $input->getOffset() <= $constraint->max) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->atPath('offset')
            ->setParameter('{{ value }}', (string) $input->getOffset())
            ->addViolation();
    }
}
