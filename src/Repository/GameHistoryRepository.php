<?php

namespace App\Repository;

use App\Entity\GameHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameHistory[]    findAll()
 * @method GameHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameHistory::class);
    }

    /**
     * @param GameHistory $gameHistory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(GameHistory $gameHistory)
    {
        $this->_em->persist($gameHistory);
        $this->_em->flush();
    }
}
