<?php

namespace App\Factory;

use App\Entity\GameHistory;
use App\Repository\GameHistoryRepository;
use App\Request\GameRequest;

/**
 * Class GameHistoryFactory
 * @package App\Factory
 */
class GameHistoryFactory
{
    /**
     * @var GameHistoryRepository
     */
    private GameHistoryRepository $gameHistoryRepository;

    /**
     * GameHistoryFactory constructor.
     * @param GameHistoryRepository $gameHistoryRepository
     */
    public function __construct(GameHistoryRepository $gameHistoryRepository)
    {
        $this->gameHistoryRepository = $gameHistoryRepository;
    }

    /**
     * @param GameRequest $request
     * @return GameHistory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createFromGameRequest(GameRequest $request): GameHistory
    {
        $gameHistory = new GameHistory();
        $gameHistory->setValue($request->value);

        $this->gameHistoryRepository->save($gameHistory);

        return $gameHistory;
    }
}
