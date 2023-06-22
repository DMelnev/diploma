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

    public function getLast(User $user, int $count = 3)
    {
        return $this->createQueryBuilder('shows')
            ->orderBy('shows.id', 'DESC')
            ->andWhere('shows.user = :user')
            ->setParameter('user', $user)
            ->leftJoin('shows.product', 'product')
            ->addSelect('product')
            ->setMaxResults(':count')
            ->setParameter('count', $count)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ShowHistory[] Returns an array of ShowHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShowHistory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
