<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

final class ChainNormalizer extends AbstractNormalizer
{
    /**
     * @var NormalizerInterface[]
     */
    private array $normalizers;

    /**
     * @param NormalizerInterface[] $normalizers
     */
    public function __construct(array $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    /**
     * {@inheritdoc}
     */
    protected function process($value, string $operator)
    {
        foreach ($this->normalizers as $normalizer) {
            $value = $normalizer->normalize($value, $operator);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsNormalization($value, string $operator): bool
    {
        return \is_scalar($value);
    }
}
