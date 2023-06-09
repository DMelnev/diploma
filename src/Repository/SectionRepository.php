<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Section>
 *
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function add(Section $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Section $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getArray(): array
    {
        $result = $this->createQueryBuilder('s')
            ->andWhere('s.id > 0')
            ->leftJoin('s.parent', 'g')
            ->addSelect('g')
            ->getQuery()
            ->getResult();
        $list = [];
        /** @var Section $item */
        foreach ($result as $item) {
            if ($item->getParent() == null) {
                $list[$item->getName()] = $item;
            } else {
                $list[$item->getParent()->getName()][0] = $item->getParent();
                $list[$item->getParent()->getName()][] = $item;
            }
        }
        return $list;
    }

    public function findOneById(int $id): ?Section
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.parent', 'pt')
            ->addSelect('pt')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
