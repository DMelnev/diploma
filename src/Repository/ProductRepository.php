<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
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

    public function getSorted()
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
            ->orderBy('p.id', 'DESC')
            ->groupBy('p');
    }
}
