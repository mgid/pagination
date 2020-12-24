<?php declare(strict_types=1);

namespace Mgid\Component\Pagination;

use Mgid\Component\Pagination\Mapping\MappingInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Mgid\Component\Pagination\Normalizer\NormalizerInterface;

interface PaginatorInterface
{
    /**
     * @return MappingInterface
     */
    public function getMapping(): MappingInterface;

    /**
     * @param string $queryBuilderClassName
     * @param string $adapterClassName
     */
    public function addAdapter(string $queryBuilderClassName, string $adapterClassName): void;

    /**
     * @param string              $queryBuilderClassName
     * @param NormalizerInterface $normalizer
     */
    public function addNormalizer(string $queryBuilderClassName, NormalizerInterface $normalizer): void;

    /**
     * @param object         $queryBuilder
     * @param InputInterface $input
     *
     * @return OutputInterface
     *
     * @throws ValidationFailedException
     */
    public function paginate(object $queryBuilder, InputInterface $input): OutputInterface;
}
