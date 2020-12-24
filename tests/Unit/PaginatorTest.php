<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Unit;

use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Mgid\Component\Pagination\Input;
use Mgid\Component\Pagination\Adapter;
use Mgid\Component\Pagination\Paginator;
use Mgid\Component\Pagination\Normalizer;
use Mgid\Component\Pagination\InputInterface;
use Mgid\Component\Pagination\PaginatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Mgid\Component\Pagination\Mapping\MappingInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class PaginatorTest extends TestCase
{
    public function testUnknownQueryBuilder()
    {
        $this->expectExceptionMessage('No adapter found for query builder of stdClass');

        $this->getPaginator()->paginate(new \stdClass(), new Input());
    }

    public function testMapping()
    {
        $this->assertInstanceOf(MappingInterface::class, $this->getPaginator()->getMapping());
    }

    public function testInvalidLimit()
    {
        $violations = [
            'limit' => [
                'message' => 'validation.pagination.limit.invalid',
                'parameters' => ['{{ value }}' => '-1'],
            ],
        ];

        $this->assertViolations(new Input(-1), $violations);
    }

    public function testInvalidOffset()
    {
        $violations = [
            'offset' => [
                'message' => 'validation.pagination.offset.invalid',
                'parameters' => ['{{ value }}' => '-2'],
            ],
        ];

        $this->assertViolations(new Input(0, -2), $violations);
    }

    public function testInvalidOrder()
    {
        $violations = [
            'foo' => [
                'message' => 'validation.pagination.order.invalid',
                'parameters' => ['{{ order }}' => '-1'],
            ],
        ];

        $this->assertViolations(new Input(0, 0, ['foo' => '-1']), $violations);
    }

    public function testInvalidOperator()
    {
        $violations = [
            'baz' => [
                'message' => 'validation.pagination.operator.invalid',
                'parameters' => ['{{ operator }}' => 'greater'],
            ],
        ];

        $this->assertViolations(new Input(0, 0, [], ['baz' => ['greater' => 'one']]), $violations);
    }

    public function testInvalidFieldName()
    {
        $violations = [
            'q-q' => [
                'message' => 'validation.pagination.fieldName.invalid',
                'parameters' => ['{{ field }}' => 'q-q'],
            ],
        ];

        $this->assertViolations(new Input(0, 0, ['q-q' => 'desc']), $violations);
    }

    public function testInvalidOrdersStructure()
    {
        $violations = [
            'orders' => [
                'message' => 'validation.pagination.structure.invalid',
                'parameters' => [],
            ],
        ];

        $this->assertViolations(new Input(0, 0, [1 => 'desc']), $violations);
    }

    public function testInvalidFiltersStructure()
    {
        $violations = [
            'filters' => [
                'message' => 'validation.pagination.structure.invalid',
                'parameters' => [],
            ],
        ];

        $this->assertViolations(new Input(0, 0, [], [1 => 'gt']), $violations);
    }

    public function testInvalidFiltersOperatorsStructure()
    {
        $violations = [
            'filters.operators' => [
                'message' => 'validation.pagination.structure.invalid',
                'parameters' => [],
            ],
        ];

        $this->assertViolations(new Input(0, 0, [], ['age' => [1 => '']]), $violations);
    }

    /**
     * @param InputInterface      $input
     * @param array<string,mixed> $violations
     */
    private function assertViolations(InputInterface $input, array $violations): void
    {
        try {
            $queryBuilder = $this->createConfiguredMock(QueryBuilder::class, []);

            $this->getPaginator()->paginate($queryBuilder, $input);
        } catch (ValidationFailedException $exception) {
            /** @var ConstraintViolationInterface $violation */
            foreach ($exception->getViolations() as $violation) {
                $expected = $violations[$violation->getPropertyPath()];

                $this->assertSame($expected['message'], $violation->getMessage());
                $this->assertSame($expected['parameters'], $violation->getParameters());
            }
        }
    }

    /**
     * @return PaginatorInterface
     */
    private function getPaginator(): PaginatorInterface
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $paginator = new Paginator($validator);

        $paginator->addAdapter(QueryBuilder::class, Adapter\Doctrine\ORM\QueryBuilderAdapter::class);

        $paginator->addNormalizer(QueryBuilder::class, new Normalizer\ListNormalizer());
        $paginator->addNormalizer(QueryBuilder::class, new Normalizer\ORM\LikeNormalizer());
        $paginator->addNormalizer(QueryBuilder::class, new Normalizer\FloatNormalizer());
        $paginator->addNormalizer(QueryBuilder::class, new Normalizer\IntegerNormalizer());

        return $paginator;
    }
}
