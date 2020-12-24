<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Validator;

use Symfony\Component\Validator\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mgid\Component\Pagination\InputInterface;

final class OrderValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($input, Constraint $constraint): void
    {
        if (!$constraint instanceof Order) {
            throw new Exception\UnexpectedTypeException($constraint, Order::class); // @codeCoverageIgnore
        }

        if (!$input instanceof InputInterface) {
            throw new Exception\UnexpectedTypeException($input, InputInterface::class); // @codeCoverageIgnore
        }

        foreach ($input->getOrders() as $fieldName => $order) {
            if (false === \in_array($order, $constraint->orders, true)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath($fieldName)
                    ->setParameter('{{ order }}', (string) $order)
                    ->addViolation();
            }
        }
    }
}
