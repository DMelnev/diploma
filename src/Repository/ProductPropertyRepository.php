<?php

namespace App\Repository;

use App\Entity\ProductProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductProperty>
 *
 * @method ProductProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductProperty[]    findAll()
 * @method ProductProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductProperty::class);
    }

    public function add(ProductProperty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductProperty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function propertiesGroup(string $name)
    {
        $result = $this->createQueryBuilder('pp')
            ->distinct()
            ->leftJoin('pp.property', 'prop')
            ->addSelect('prop')
            ->leftJoin('prop.unit', 'un')
            ->addSelect('un')
            ->andWhere('prop.name LIKE :name')
            ->setParameter(':name', $name)
            ->andWhere('pp.value > 0')
            ->select('
            pp.id,
            pp.value,
            un.unit
            ')
            ->orderBy('pp.value')
            ->getQuery()
            ->getResult();
        $res = [];
        foreach ($result as $item) {
            $res[$item['value'] . ' ' . $item['unit']] = $item['value'];
        }
        return $res;

    }
}
