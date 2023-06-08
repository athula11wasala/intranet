<?php

namespace App\Repository;

use App\Entity\Teams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Teams>
 *
 * @method Teams|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teams|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teams[]    findAll()
 * @method Teams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teams::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Teams $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Teams $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Teams[] Returns an array of Teams objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Teams
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * add team
     */
    public function addTeam( $dataArr = []) {

    
        try {
            $team = new Teams();
            $team->setName($dataArr['name']);
            $team->setCountry($dataArr['country']);
            $em = $this->getEntityManager();
            $em->persist($team);
            $em->flush();

        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;
    }
    
     /**
      * edit team
      */
    public function editTeam($team = null, $dataArr = [] ) {

      
        try {
            $team->setName($dataArr['name']->getData());
            $team->setCountry($dataArr['country']->getdata());
            $em = $this->getEntityManager();
            $em->persist($team);
            $em->flush();

        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;
    }

    public function deleteTeam($team){
        try {
            $em = $this->getEntityManager();
            $em->remove($team);
            $em->flush();
        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;

    }

    public function getTeam($teamId = null)
    {
         $data = $this->createQueryBuilder('team')
            ->select('team.name','team.id');

            if(!empty($teamId)){
              $data =   $data->andWhere('team.id != :val')
                       ->setParameter('val', $teamId);
            }
    
         return $data->getQuery()->getResult();
       
    }

    public function getTeamInfo()
    {
        $teamData = [];
        
         $data = $this->createQueryBuilder('team')
            ->select('team.name','team.id')
            ->getQuery()
            ->getResult();

        foreach ($data as $value) {
            
            $teamData[$value['id']] =  $value['name'];
        }
        return $teamData;
    }
}
