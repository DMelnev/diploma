<?php

namespace App\Repository;

use App\Entity\DayOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DayOffer>
 *
 * @method DayOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayOffer[]    findAll()
 * @method DayOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayOffer::class);
    }

    public function add(DayOffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DayOffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOffer()
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.product', 'p')
            ->addSelect('p')
            ->leftJoin('p.prices', 'pr')
            ->addSelect('pr')
            ->leftJoin('p.action', 'ac')
            ->addSelect('ac')
            ->leftJoin('p.section', 'sn')
            ->addSelect('sn')
            ->leftJoin('sn.parent', 'sng')
            ->select('
            p.name,
            p.id,
            MAX(pr.price) AS price,
            ac.discount AS discount,
            sn.name AS section,
            sng.name AS group,
            o.picture AS link,
            o.until
            ')
            ->groupBy('p, o')
            ->getQuery()
            ->getOneOrNullResult();
    }

}
