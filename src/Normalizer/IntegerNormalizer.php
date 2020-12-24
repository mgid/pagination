<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

final class IntegerNormalizer extends AbstractNormalizer
{
    /**
     * {@inheritdoc}
     */
    protected function process($value, string $operator): int
    {
        return \intval($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsNormalization($value, string $operator): bool
    {
        return \is_string($value) && \is_numeric($value) && $value <= \PHP_INT_MAX && false === \strpos($value, '.');
    }
}
