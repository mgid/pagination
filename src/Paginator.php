<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

use Mgid\Component\Pagination\Mapping\Mapping;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Mgid\Component\Pagination\Mapping\MappingInterface;
use Mgid\Component\Pagination\Normalizer\ChainNormalizer;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Mgid\Component\Pagination\Normalizer\NormalizerInterface;

final class Paginator implements PaginatorInterface
{
    /**
     * @var array<string,string>
     */
    private array $adapters = [];

    /**
     * @var array<string,array<NormalizerInterface>>
     */
    private array $normalizers = [];

    /**
     * @var MappingInterface
     */
    private MappingInterface $mapping;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->mapping = new Mapping();
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getMapping(): MappingInterface
    {
        return $this->mapping;
    }

    /**
     * {@inheritdoc}
     */
    public function addAdapter(string $queryBuilderClassName, string $adapterClassName): void
    {
        $this->adapters[$queryBuilderClassName] = $adapterClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function addNormalizer(string $queryBuilderClassName, NormalizerInterface $normalizer): void
    {
        $this->normalizers[$queryBuilderClassName][] = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(object $queryBuilder, InputInterface $input): OutputInterface
    {
        $this->validate($input);

        $adapter = $this->getAdapter($queryBuilder);
        $normalizer = $this->getNormalizer(\get_class($queryBuilder));

        $this->mapping->setRootAlias($adapter->getRootAlias());
        $this->mapping->normalize($input, $normalizer);

        $adapter->addFilters($input);

        $items = [];
        $count = $adapter->getCount();

        if ($count > 0) {
            $adapter->addOrders($input);
            $adapter->addPagination($input);

            $items = $adapter->getItems();
        }

        return new Output($count, $items);
    }

    /**
     * @param object $queryBuilder
     *
     * @return Adapter\QueryBuilderAdapterInterface
     */
    private function getAdapter(object $queryBuilder): Adapter\QueryBuilderAdapterInterface
    {
        foreach ($this->adapters as $queryBuilderClassName => $adapterClassName) {
            if ($queryBuilder instanceof $queryBuilderClassName) {
                return new $adapterClassName($queryBuilder);
            }
        }

        throw new \RuntimeException(\sprintf('No adapter found for query builder of %s', \get_class($queryBuilder)));
    }

    /**
     * @param string $queryBuilderClassName
     *
     * @return NormalizerInterface
     */
    private function getNormalizer(string $queryBuilderClassName): NormalizerInterface
    {
        return new ChainNormalizer($this->normalizers[$queryBuilderClassName] ?? []);
    }

    /**
     * @param InputInterface $input
     *
     * @throws ValidationFailedException
     */
    private function validate(InputInterface $input): void
    {
        $violations = $this->validator->validate($input);

        if ($violations->count()) {
            throw new ValidationFailedException($input, $violations);
        }
    }
}
