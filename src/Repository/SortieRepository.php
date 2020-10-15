<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /*public function findSearch(SearchData $search, UserInterface $user): PaginationInterface
    {
        $query = $this->getSearchQuery($search,$user)->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    } */

    public function findSearch(SearchData $search, UserInterface $user): QueryBuilder
    {
        //$sorties = $sortiesRepo->findAll();

        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.siteOrganisateur', 'c')
            ->join('p.organisateur',  'o');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->dateMin)) {
            $query = $query
                ->andWhere('p.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $search->dateMin);
        }

        if (!empty($search->dateMax)) {
            $query = $query
                ->andWhere('p.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $search->dateMax);
        }

        if (!empty($search->campus)) {
            $query = $query
                ->andWhere('c.idCampus IN (:campus)')
                ->setParameter('campus', $search->campus);
        }

        if (!empty($search->organisateur)) {
            $query = $query
                ->andWhere('$user->getUsername() == p.organisateur.username');
        }

        if (!empty($search->inscrit)) {
            $query = $query
                ->andWhere('$user->getUsername() IN p.participants.username');
        }

        if (!empty($search->nonInscrit)) {
            $query = $query
                ->andWhere('$user->getUsername() NOT IN p.participants.username');
        }

        if (!empty($search->fini)) {
            $query = $query
                ->andWhere('p.dateLimiteInscription >= NOW()');
        }

        //$query2 = $query->getQuery()->execute();
        return $query;
    }


    /* public function search($nom) {
        return $this->createQueryBuilder('Sortie')
            ->andWhere('Sortie.nom LIKE :nom')
            ->setParameter('nom','%'.$nom.'%')
            ->getQuery()
            ->execute();
    } */




    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
