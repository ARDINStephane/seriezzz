<?php

namespace App\Application\Doctrine\Repository;

use App\Application\Common\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class DoctrineBaseRepository
 * @package App\Application\Doctrine\Repository
 */
abstract class DoctrineBaseRepository extends ServiceEntityRepository implements BaseRepository
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;
    /** @var string */
    protected $class;

    /**
     * @param array $List
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveBatch(array $List): void
    {
        $em = $this->registry->getManager();

        foreach ($List as $item) {
            $em->persist($item);
        }


        $em->flush();
    }

    /**
     * DoctrineBaseRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->class);
        $this->registry = $registry;
    }

    /**
     * @param string $alias
* @param null $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias = 'o', $indexBy = null): QueryBuilder
    {
        return parent::createQueryBuilder($alias, $indexBy);
    }

    /**
     * @param $object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($object): void
    {
        $em = $this->registry->getManager();

        $em->persist($object);
        $em->flush();
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param array ...$args
     * @return mixed
     */
    public function new(...$args)
    {
        $class = $this->getClass();
        return new $class(...$args);
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->createQueryBuilder()
            ->delete($this->class,'o')
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
