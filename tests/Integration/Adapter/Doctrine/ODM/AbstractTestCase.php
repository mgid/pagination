<?php declare(strict_types=1);

namespace Mgid\Component\Pagination\Tests\Integration\Adapter\Doctrine\ODM;

use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use Doctrine\ODM\MongoDB\Query;
use Doctrine\ODM\MongoDB\Aggregation;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Validator\Validation;
use Mgid\Component\Pagination\Adapter;
use Mgid\Component\Pagination\Paginator;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Mgid\Component\Pagination\PaginatorInterface;

abstract class AbstractTestCase extends TestCase
{
    protected function setUp(): void
    {
        $documents = [
            new Document\Demo('bbb', 21),
            new Document\Demo('ddd', 18),
            new Document\Demo('eee', 20),
            new Document\Demo('aaa', 25),
            new Document\Demo('fff', 22),
            new Document\Demo('ccc', 23),
        ];

        foreach ($documents as $document) {
            $this->getDocumentManager()->persist($document);
        }

        $this->getDocumentManager()->flush();
    }

    protected function tearDown(): void
    {
        foreach ($this->getDocumentManager()->getRepository(Document\Demo::class)->findAll() as $document) {
            $this->getDocumentManager()->remove($document);
        }

        $this->getDocumentManager()->flush();
    }

    /**
     * @return PaginatorInterface
     */
    protected function getPaginator(): PaginatorInterface
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $paginator = new Paginator($validator);

        $paginator->addAdapter(Query\Builder::class, Adapter\Doctrine\ODM\QueryBuilderAdapter::class);
        $paginator->addAdapter(Aggregation\Builder::class, Adapter\Doctrine\ODM\AggregationBuilderAdapter::class);

        return $paginator;
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager(): DocumentManager
    {
        static $dm = null;

        if (null === $dm) {
            $configuration = new Configuration();
            $configuration->setHydratorDir(__DIR__ . '/Hydrators/');
            $configuration->setHydratorNamespace(__NAMESPACE__);
            $configuration->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/Documents'));

            $driverOptions = ['typeMap' => ['root' => 'array', 'document' => 'array']];

            $client = new Client('mongodb://mongo.intra/', [], $driverOptions);

            $dm = DocumentManager::create($client, $configuration);
        }

        return $dm;
    }
}
