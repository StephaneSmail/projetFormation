<?php

namespace App\Repository;

use App\Entity\Posseder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Posseder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posseder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posseder[]    findAll()
 * @method Posseder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PossederRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Posseder::class);
    }

    public function getUsed($id_materiel)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
                                    SELECT p
                                    FROM App\Entity\Posseder p
                                    WHERE p.materiels = :id');
        $query->setParameter('id', $id_materiel);
        return $query->getResult();
    }


    // /**
    //  * @return Posseder[] Returns an array of Posseder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Posseder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
