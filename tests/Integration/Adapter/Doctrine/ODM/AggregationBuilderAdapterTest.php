<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Integration\Adapter\Doctrine\ODM;

use Doctrine\ODM\MongoDB\Aggregation\Builder;
use Mgid\Component\Pagination\Input;

final class AggregationBuilderAdapterTest extends AbstractTestCase
{
    public function testPaginate()
    {
        $items = [];
        $input = new Input(1, 1, ['name' => 'desc'], ['age' => ['gt' => 20], 'name' => ['neq' => 'fff']]);
        $output = $this->getPaginator()->paginate($this->getQueryBuilder(), $input);

        foreach ($output->getItems() as $item) {
            $items[] = $item['name'];
        }

        $this->assertSame(3, $output->getCount());
        $this->assertSame(['bbb'], $items);
    }

    /**
     * @return Builder
     */
    private function getQueryBuilder(): Builder
    {
        return new Builder($this->getDocumentManager(), Document\Demo::class);
    }
}
