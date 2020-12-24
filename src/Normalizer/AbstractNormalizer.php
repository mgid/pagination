<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

abstract class AbstractNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    final public function normalize($value, string $operator)
    {
        if ($this->supportsNormalization($value, $operator)) {
            return $this->process($value, $operator);
        }

        return $value;
    }

    /**
     * @param mixed  $value
     * @param string $operator
     *
     * @return mixed
     */
    abstract protected function process($value, string $operator);

    /**
     * @param mixed  $value
     * @param string $operator
     *
     * @return bool
     */
    abstract protected function supportsNormalization($value, string $operator): bool;
}
