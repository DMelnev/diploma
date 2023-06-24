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
        pp.link as link,
        ac.discount as discount
        FROM product p
        LEFT JOIN action ac ON ac.id = p.action_id
        LEFT JOIN price pr ON pr.product_id = p.id
        LEFT JOIN section s on p.section_id = s.id
        LEFT JOIN section_group sg on s.parent_id = sg.id
        LEFT JOIN product_picture pp on p.id = pp.product_id
        WHERE ac.until > CURRENT_TIMESTAMP()
        ORDER BY RAND()
        LIMIT %d
        ', $count);
        $stmt = $connect->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function getLimited(int $count, int $dayOfferId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = sprintf("
        SELECT
	p.name AS name,
	p.id AS id,
	max(pr.price) AS price,
    (SELECT pp.link FROM product_picture AS pp WHERE pp.product_id = p.id LIMIT 1) AS link,
    ac.discount AS discount,
    sn.name AS section,
	sng.name AS `group`
FROM product AS p
LEFT JOIN price AS pr ON p.id = pr.product_id
LEFT JOIN `action` AS ac ON ac.id = p.action_id
LEFT JOIN section as sn ON sn.id = p.section_id
LEFT JOIN section_group as sng ON sng.id = sn.parent_id
WHERE p.limited = true and p.id != :dayOffer
GROUP BY id DESC
LIMIT %d", $count);
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(["dayOffer" => $dayOfferId]);
        return $result->fetchAllAssociative();
    }
}
