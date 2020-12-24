<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Mapping;

use Mgid\Component\Pagination\InputInterface;
use Mgid\Component\Pagination\Normalizer\NormalizerInterface;

interface MappingInterface
{
    /**
     * @return ConstraintInterface
     */
    public function getConstraint(): ConstraintInterface;

    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): void;

    /**
     * @param string|null $rootAlias
     */
    public function setRootAlias(?string $rootAlias): void;

    /**
     * @param array<string,string> $associations
     */
    public function setAssociations(array $associations): void;

    /**
     * @param InputInterface      $input
     * @param NormalizerInterface $normalizer
     */
    public function normalize(InputInterface $input, NormalizerInterface $normalizer): void;
}
