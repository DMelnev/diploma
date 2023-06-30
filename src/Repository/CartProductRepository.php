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
            ->getOneOrNullResult();
//        dd($result);
        return $result;

    }

    public function getTopProducts($count = 8)
    {
//        $result = $this->createQueryBuilder('cp')
//            ->distinct()
//            ->leftJoin('cp.price', 'pr')
//            ->addSelect('pr')
//            ->leftJoin('pr.product', 'p')
//            ->addSelect('p')
//            ->innerJoin('p.productPictures', 'pp')
//            ->addSelect('pp')
//            ->leftJoin('p.action', 'ac')
//            ->addSelect('ac')
//            ->leftJoin('p.section', 'sn')
//            ->addSelect('sn')
//            ->leftJoin('sn.parent', 'sng')
//            ->select("
//            p.name AS name,
//	            p.id AS id,
//              COUNT(p.id) AS sm,
//	            max(pr.price) AS price,
//              (SELECT pp.link FROM product_picture AS pp WHERE pp.product_id = p.id LIMIT 1) AS link,
//              ac.discount AS discount,
//              sn.name AS section,
//	            sng.name AS `group`
//            ")
//            ->andWhere('cp.id > 0')
//            ->orderBy('sm', 'DESC')
//            ->groupBy('p')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult();
        $conn = $this->getEntityManager()->getConnection();
        $sql = sprintf("
        SELECT
	p.name AS name,
	p.id AS id,
    COUNT(p.id) AS sm,
	max(pr.price) AS price,
    p.picture,
    ac.discount AS discount,
    sn.name AS section,
	sng.name AS `group`
FROM cart_product AS cp
LEFT JOIN price AS pr ON cp.price_id = pr.id
LEFT JOIN product AS p ON p.id = pr.product_id
LEFT JOIN `action` AS ac ON ac.id = p.action_id
LEFT JOIN section as sn ON sn.id = p.section_id
LEFT JOIN section_group as sng ON sng.id = sn.parent_id
WHERE cp.id > 0
GROUP BY id
ORDER BY sm DESC
LIMIT %d", $count);
        $stmt = $conn->prepare($sql);
//        $stmt->bindValue(1, 8);
        $result = $stmt->executeQuery();
//        dd($result->fetchAllAssociative());
        return $result->fetchAllAssociative();
    }

}
