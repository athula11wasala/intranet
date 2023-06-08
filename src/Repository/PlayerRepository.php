<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Player $entity, bool $flush = true): void
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
    public function remove(Player $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Player[] Returns an array of Player objects
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
    public function findOneBySomeField($value): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     /**
     * add team
     */
    public function addPlayer( $name,$surName,$teamId) {

        try {
            $em = $this->getEntityManager();
            $em->beginTransaction();
            $player = new Player();
            $player->setName($name);
            $player->setSurname($surName);
            $player->setTeamId($teamId);
         
            $em->persist($player);
            $em->flush();
            $em->commit();

        }
        catch (Exception $ex) {

            $em->rollback();
            throw $ex;
        }
        return true;
    }

      /**
      * edit team
      */
      public function editPlayer($player = null, $dataArr = [] ) {

      
        try {
            $player->setName($dataArr['name']->getData());
            $player->setsurname($dataArr['surname']->getdata());
            $em = $this->getEntityManager();
            $em->persist($player);
            $em->flush();

        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;
    }

    public function deletePlayer($player){
        try {
            $em = $this->getEntityManager();
            $em->remove($player);
            $em->flush();
        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;

    }

    public function getPlayerInfo()
    {
         return  $this->createQueryBuilder('player')
            ->select('player.name','player.id','player.surname','player.team_id')
            ->getQuery()
            ->getResult();

    }

    public function getPlayerByTeam($teamId)
    {
        return $data = $this->createQueryBuilder('player')
            ->select('player.name','player.id')
            ->andWhere('player.team_id = :val')
            ->setParameter('val', $teamId)
            ->getQuery()
            ->getResult();

        foreach ($data as $value) {
            
            $teamData[$value['name']] =  $value['name'];
        }
        return [];
    }

     /**
      * edit team
      */
      public function playerAssingTeam($player = null, $dataArr ) {

        try {
            $player->setTeamId($dataArr['assignteam']);
            $em = $this->getEntityManager();
            $em->persist($player);
            $em->flush();

        }
        catch (Exception $ex) {

            print_r($ex->getMessage());
            exit();
        }
        return true;
    }

    public function getPlayerWithTeamInfo()
    {
        $em = $this->getEntityManager();
        return  $em->createQuery('select p.name,
                                  p.id,p.surname,p.team_id,t.name  as team,
                                  t.country 
                                  from  App\Entity\Player p
                                  left Join App\Entity\Teams t with p.team_id = t.id')
                                  ->execute();
    }
}
