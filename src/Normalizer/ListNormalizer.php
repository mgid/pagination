<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

use Mgid\Component\Pagination\Contract\FilterableInterface;

final class ListNormalizer extends AbstractNormalizer
{
    /**
     * @param mixed  $value
     * @param string $operator
     *
     * @return string[]
     */
    protected function process($value, string $operator): array
    {
        return \explode(',', $value) ?: [];
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsNormalization($value, string $operator): bool
    {
        return \is_string($value) && \in_array($operator, [FilterableInterface::IN, FilterableInterface::NOT_IN], true);
    }
}
