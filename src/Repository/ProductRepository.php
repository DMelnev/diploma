<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use App\Service\SortConst;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements SortConst
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRandomAction(int $count): array
    {


        $connect = $this->getEntityManager()->getConnection();
        $sql = sprintf('
        SELECT DISTINCT 
        pr.price as price,
        s.name as section,
        sg.name as `group`,
        p.name,
        p.id,
        p.picture,
        ac.discount as discount
        FROM product p
        LEFT JOIN action ac ON ac.id = p.action_id
        LEFT JOIN price pr ON pr.product_id = p.id
        LEFT JOIN section s on p.section_id = s.id
        LEFT JOIN section_group sg on s.parent_id = sg.id
        WHERE ac.until > CURRENT_TIMESTAMP()
        ORDER BY RAND()
        LIMIT %d
        ', $count);
        $stmt = $connect->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function getLimited(int $count, int $dayOfferId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id > 0')
            ->leftJoin('p.prices', 'pr')
            ->addSelect('pr')
            ->leftJoin('p.action', 'ac')
            ->addSelect('ac')
            ->leftJoin('p.section', 'sn')
            ->addSelect('sn')
            ->leftJoin('sn.parent', 'sng')
            ->addSelect('sng')
            ->select("
            p.name AS name,
	        p.id AS id,
	        max(pr.price) AS price,
            p.picture,
            ac.discount AS discount,
            sn.name AS section,
	        sng.name AS group
	        ")
            ->andWhere('p.limited = true')
            ->andWhere('p.id != :dayOffer')
            ->setParameter('dayOffer', $dayOfferId)
            ->orderBy('p.id', 'DESC')
            ->groupBy('p')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    private function getHandler(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id > 0')
            ->leftJoin('p.prices', 'pr')
            ->addSelect('pr')
            ->andWhere('pr.price > 0')
            ->leftJoin('p.action', 'ac')
            ->addSelect('ac')
            ->leftJoin('p.section', 'sn')
            ->addSelect('sn')
            ->leftJoin('sn.parent', 'sng')
            ->addSelect('sng')
            ->leftJoin('pr.cartProducts', 'cp')
            ->addSelect('cp')
            ->leftJoin('cp.cart', 'cart')
            ->addSelect('cart')
            ->leftJoin('p.feedback', 'f')
            ->addSelect('f.')
            ->leftJoin('p.productProperties', 'pprop')
            ->addSelect('pprop')
            ->leftJoin('pprop.property', 'prop')
            ->addSelect('prop');


    }

    public function getSorted(array $sortArray, array $filter): QueryBuilder
    {
        $builder = $this->getHandler()
            ->select("
            COUNT(f.product) as cf,
            COUNT(pr.product) as ccp,
            p.name AS name,
	        p.id AS id,
	        avg(pr.price) AS price,
            p.picture,
            ac.discount AS discount,
            sn.name AS section,
	        sng.name AS group
	        
	        ");
        $this->getFilter($filter, $builder);

        foreach ($sortArray as $key => $sort) {
            if ($sort != self::ASC && $sort != self::DESC) continue;
            switch ($key) {
                case self::SORT_RANK:
                    $builder->orderBy('ccp', $sort);
                    break;
                case self::SORT_PRICE:
                    $builder->orderBy('price', $sort);
                    break;
                case self::SORT_COMMENT:
                    $builder->orderBy('cf', $sort);
                    break;
                case self::SORT_NEW:
                    $builder->orderBy('id', $sort);
                    break;
            }
        }

        return $builder->groupBy('p.id');
    }

    public function getMinMaxPrice(array $filter = []): array
    {
        $builder = $this->getHandler()
            ->select(["
        MAX(pr.price) as max_price,
        MIN(pr.price) as min_price
        "
            ]);

        return $this->getFilter($filter, $builder)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function getFilter(array $filter, QueryBuilder &$builder): QueryBuilder
    {
        foreach ($filter as $key => $item) {
            if (!$item) continue;
            switch ($key) {
                case self::FILTER_RANGE:
                    $builder
                        ->andWhere('pr.price >= :min')
                        ->setParameter('min', (int)($item[0] * 100))
                        ->andWhere('pr.price <= :max')
                        ->setParameter('max', (int)($item[1] * 100));
                    break;
                case self::FILTER_TITLE:
                    $builder
                        ->andWhere('p.name LIKE :name')
                        ->setParameter('name', '%' . $item . '%');
                    break;
                case self::FILTER_SELLER_:
                    /** @var User $item */
                    $builder
                        ->andWhere('pr.seller = :user')
                        ->setParameter('user', $item);
                    break;
                case self::FILTER_MEMORY_VALUE:
                    $builder
                        ->andWhere('prop.name = :name')
                        ->andWhere('pprop.value = :value')
                        ->setParameter('name', 'Объем памяти')
                        ->setParameter('value', $item);
                    break;
                case self::FILTER_SECTION:
                    $builder
                        ->andWhere('sn.id = :id')
                        ->setParameter('id', $item);
            }
        }
        return $builder;
    }
}
