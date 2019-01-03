<?php

namespace DentalClinicBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * PatientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PatientRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param int $currentPage
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getAllPatients($currentPage)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.fullName', 'ASC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage);

       return $paginator;
    }

    /**
     * @param \Doctrine\ORM\Query $dql
     * @param integer            $page
     * @param integer            $limit
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page, $limit=10 )
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function findByPhone($filter, $currentPage)
    {
        $query = $this->createQueryBuilder('p')
        ->where('p.phone like :phone')
        ->setParameter('phone', '%'.$filter.'%')
        ->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }
}
