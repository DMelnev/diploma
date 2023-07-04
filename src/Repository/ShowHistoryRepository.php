<?php

namespace App\Repository;

use App\Entity\ShowHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShowHistory>
 *
 * @method ShowHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShowHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShowHistory[]    findAll()
 * @method ShowHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShowHistory::class);
    }

    public function add(ShowHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShowHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLast(User $user, int $count)
    {
        $result = $this->createQueryBuilder('s')
            ->distinct()
            ->leftJoin('s.product', 'p')
            ->addSelect('p')
            ->leftJoin('p.prices', 'pr')
            ->addSelect('pr')
            ->leftJoin('p.action', 'ac')
            ->addSelect('ac')
            ->leftJoin('p.section', 'sn')
            ->addSelect('sn')
            ->leftJoin('sn.parent', 'sng')
            ->select("
            s.id AS sh,
            p.name,
            p.id,
            p.picture,
            pr.price AS price,
            ac.discount AS discount,
            sn.name AS section,
            sng.name AS group
            ")
            ->andWhere('s.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();

//        dd($result);
        return $result;
    }

}
