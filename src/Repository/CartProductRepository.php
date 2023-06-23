<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\CartProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProduct>
 *
 * @method CartProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProduct[]    findAll()
 * @method CartProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    public function add(CartProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getSum(Cart $cart)
    {
        $result = $this->createQueryBuilder('p')
            ->andWhere('p.cart = :cart')
            ->setParameter('cart', $cart)
            ->leftJoin('p.price', 'price')
            ->addSelect('price')
            ->select('SUM(price.price)')
            ->getQuery()
            ->getResult();
//        dd($result);
        return (array_key_exists(0, $result) && array_key_exists(1, $result[0]))
            ? $result[0][1]
            : 0;

    }

}
