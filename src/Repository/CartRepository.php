<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function add(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLast(User $user)
    {
        $result = $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC')
            ->leftJoin('c.cartProducts', 'products')
            ->addSelect('products')
            ->leftJoin('products.price', 'price')
            ->addSelect('price')
            ->leftJoin('c.deliveryBy', 'delivery')
            ->addSelect('delivery')
            ->leftJoin('c.payBy', 'pay')
            ->addSelect('pay')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->select('
            c.id, c.createdAt, 
            SUM(price.price) AS sum, 
            delivery.name AS delivery_by, 
            pay.description AS pay_by,
            c.status')
            ->groupBy('c')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        return key_exists(0, $result) ? $result[0] : null;
    }

    public function getAll(User $user)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC')
            ->leftJoin('c.cartProducts', 'products')
            ->addSelect('products')
            ->leftJoin('products.price', 'price')
            ->addSelect('price')
            ->leftJoin('c.deliveryBy', 'delivery')
            ->addSelect('delivery')
            ->leftJoin('c.payBy', 'pay')
            ->addSelect('pay')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->select('
            c.id, c.createdAt, 
            SUM(price.price) AS sum, 
            delivery.name AS delivery_by, 
            pay.description AS pay_by,
            c.status')
            ->groupBy('c')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
