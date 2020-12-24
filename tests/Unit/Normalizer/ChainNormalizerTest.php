<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit\Normalizer;

use PHPUnit\Framework\TestCase;
use Mgid\Component\Pagination\Normalizer\ListNormalizer;
use Mgid\Component\Pagination\Normalizer\ChainNormalizer;
use Mgid\Component\Pagination\Normalizer\FloatNormalizer;
use Mgid\Component\Pagination\Contract\FilterableInterface;
use Mgid\Component\Pagination\Normalizer\IntegerNormalizer;
use Mgid\Component\Pagination\Normalizer\ORM\LikeNormalizer;

final class ChainNormalizerTest extends TestCase
{
    public function testORMNormalize(): void
    {
        $normalizer = new ChainNormalizer([
            new ListNormalizer(),
            new LikeNormalizer(),
        ]);

        $this->assertSame('1', $normalizer->normalize('1', FilterableInterface::EQUAL));
        $this->assertSame('123.45', $normalizer->normalize('123.45', FilterableInterface::NOT_EQUAL));
        $this->assertSame(['2'], $normalizer->normalize('2', FilterableInterface::IN));
        $this->assertSame(['3'], $normalizer->normalize('3', FilterableInterface::NOT_IN));
        $this->assertSame('2000-01-01', $normalizer->normalize('2000-01-01', FilterableInterface::GREATER_THEN));
        $this->assertSame('%foo%', $normalizer->normalize('foo', FilterableInterface::LIKE));
    }

    public function testODMNormalize(): void
    {
        $normalizer = new ChainNormalizer([
            new ListNormalizer(),
            new IntegerNormalizer(),
            new FloatNormalizer(),
        ]);

        $this->assertSame(1, $normalizer->normalize('1', FilterableInterface::EQUAL));
        $this->assertSame(123.45, $normalizer->normalize('123.45', FilterableInterface::NOT_EQUAL));
        $this->assertSame(['2'], $normalizer->normalize('2', FilterableInterface::IN));
        $this->assertSame(['3'], $normalizer->normalize('3', FilterableInterface::NOT_IN));
        $this->assertSame('2000-01-01', $normalizer->normalize('2000-01-01', FilterableInterface::GREATER_THEN));
        $this->assertSame('foo', $normalizer->normalize('foo', FilterableInterface::LIKE));
    }
}
