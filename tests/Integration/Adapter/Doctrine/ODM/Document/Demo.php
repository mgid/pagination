<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Integration\Adapter\Doctrine\ODM\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="tests", collection="demo")
 */
final class Demo
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field
     */
    private $name;

    /**
     * @MongoDB\Field(type="int")
     */
    private $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}
