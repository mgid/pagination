<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Mapping;

use Mgid\Component\Pagination\InputInterface;
use Mgid\Component\Pagination\Normalizer\NormalizerInterface;

final class Mapping implements MappingInterface
{
    /**
     * @var string
     */
    private string $delimiter = '.';

    /**
     * @var string|null
     */
    private ?string $rootAlias = null;

    /**
     * @var array<string,string>
     */
    private array $associations = [];

    /**
     * @var ConstraintInterface
     */
    private ConstraintInterface $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    /**
     * @return ConstraintInterface
     */
    public function getConstraint(): ConstraintInterface
    {
        return $this->constraint;
    }

    /**
     * {@inheritdoc}
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function setRootAlias(?string $rootAlias): void
    {
        $this->rootAlias = $rootAlias;
    }

    /**
     * {@inheritdoc}
     */
    public function setAssociations(array $associations): void
    {
        $this->associations = $associations;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(InputInterface $input, NormalizerInterface $normalizer): void
    {
        $orders = $filters = [];

        foreach ($input->getOrders() as $fieldName => $order) {
            if ($this->constraint->isAllowedOrder($fieldName)) {
                $orders[$this->resolve($fieldName)] = $order;
            }
        }

        foreach ($input->getFilters() as $fieldName => $operators) {
            if ($this->constraint->isAllowedFilter($fieldName)) {
                foreach ($operators as $operator => $value) {
                    $filters[$this->resolve($fieldName)][$operator] = $normalizer->normalize($value, $operator);
                }
            }
        }

        $input->setOrders($orders);
        $input->setFilters($filters);
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    private function resolve(string $fieldName): string
    {
        if (isset($this->associations[$fieldName])) {
            return $this->associations[$fieldName];
        }

        if (null !== $this->rootAlias) {
            return \implode($this->delimiter, [$this->rootAlias, $fieldName]);
        }

        return $fieldName;
    }
}
