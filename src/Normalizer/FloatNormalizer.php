<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

final class FloatNormalizer extends AbstractNormalizer
{
    /**
     * {@inheritdoc}
     */
    protected function process($value, string $operator): float
    {
        return \floatval($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsNormalization($value, string $operator): bool
    {
        return \is_string($value) && \is_numeric($value) && $value <= \PHP_FLOAT_MAX && false !== \strpos($value, '.');
    }
}
