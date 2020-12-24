<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer\ORM;

use Mgid\Component\Pagination\Contract\FilterableInterface;
use Mgid\Component\Pagination\Normalizer\AbstractNormalizer;

final class LikeNormalizer extends AbstractNormalizer
{
    /**
     * {@inheritdoc}
     */
    protected function process($value, string $operator)
    {
        return '%' . $value . '%';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsNormalization($value, string $operator): bool
    {
        return \is_scalar($value) && $operator === FilterableInterface::LIKE;
    }
}
