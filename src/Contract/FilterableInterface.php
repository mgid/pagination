<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Contract;

interface FilterableInterface
{
    public const EQUAL = 'eq';
    public const NOT_EQUAL = 'neq';
    public const IN = 'in';
    public const NOT_IN = 'nin';
    public const LESS_THEN = 'lt';
    public const LESS_THEN_OR_EQUAL = 'lte';
    public const GREATER_THEN = 'gt';
    public const GREATER_THEN_OR_EQUAL = 'gte';
    public const LIKE = 'like';

    public const OPERATORS = [
        self::EQUAL,
        self::NOT_EQUAL,
        self::IN,
        self::NOT_IN,
        self::LESS_THEN,
        self::LESS_THEN_OR_EQUAL,
        self::GREATER_THEN,
        self::GREATER_THEN_OR_EQUAL,
        self::LIKE,
    ];

    /**
     * @param array<string,array<string,string>> $filters
     */
    public function setFilters(array $filters): void;

    /**
     * Example:
     * <code>
     *  [
     *      'name' => ['eq' => 'Test'],
     *      'price' => ['gte' => '0.1', 'lt' => '2.5'],
     *      'status' => ['in' => '1,2,5'],
     *  ]
     * </code>
     *
     * @return array<string,array<string,string>>
     */
    public function getFilters(): array;
}
