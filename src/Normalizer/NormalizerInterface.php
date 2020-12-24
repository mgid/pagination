<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Normalizer;

interface NormalizerInterface
{
    /**
     * @param mixed  $value
     * @param string $operator
     *
     * @return mixed
     */
    public function normalize($value, string $operator);
}
