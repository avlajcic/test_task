<?php

namespace App\Service;

use App\Entity\GameHistory;
use App\Entity\GameState;
use App\Exception\ApiException;
use App\Repository\GameHistoryRepository;
use App\Repository\GameStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GameHelperService
 * @package App\Service
 */
class GameHelperService
{
    /**
     * @var GameStateRepository
     */
    private GameStateRepository $gameStateRepository;

    /**
     * @var RedisService
     */
    private RedisService $redisService;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var StringCheckerService
     */
    private StringCheckerService $stringCheckerService;
    /**
     * @var GameHistoryRepository
     */
    private GameHistoryRepository $gameHistoryRepository;

    /**
     * GameHelperService constructor.
     * @param GameStateRepository $gameStateRepository
     * @param RedisService $redisService
     * @param EntityManagerInterface $entityManager
     * @param StringCheckerService $stringCheckerService
     * @param GameHistoryRepository $gameHistoryRepository
     */
    public function __construct(
        GameStateRepository $gameStateRepository,
        RedisService $redisService,
        EntityManagerInterface $entityManager,
        StringCheckerService $stringCheckerService,
        GameHistoryRepository $gameHistoryRepository
    ) {
        $this->redisService = $redisService;
        $this->gameStateRepository = $gameStateRepository;
        $this->entityManager = $entityManager;
        $this->stringCheckerService = $stringCheckerService;
        $this->gameHistoryRepository = $gameHistoryRepository;
    }

    /**
     * @return bool
     * @throws ApiException
     */
    public function getCurrentGameState(): bool
    {
        if ($this->redisService->getGameState() !== null) {
            return $this->redisService->getGameState();
        }

        $gameState = $this->gameStateRepository->findOneBy([
            'key' => GameState::GAME_STATE_KEY
        ]);

        if (!$gameState) {
            throw new ApiException('Game state not set', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $gameState->getState();
    }

    /**
     * @param bool $newState
     * @throws ApiException
     */
    public function setNewGameState(bool $newState): void
    {
        $this->redisService->saveNewGameState($newState);

        $gameState = $this->gameStateRepository->findOneBy([
            'key' => GameState::GAME_STATE_KEY
        ]);

        if (!$gameState) {
            throw new ApiException('Game state not set', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $gameState->setState($newState);
        $this->entityManager->persist($gameState);
        $this->entityManager->flush();
    }

    /**
     * @param string $value
     * @return bool
     * @throws ApiException
     */
    public function getStateForValue(string $value): bool
    {
        $cachedState = $this->redisService->getStateForValue($value);
        if ($cachedState !== null) {
            return $cachedState;
        }

        $isStringOkay = $this->stringCheckerService->doesStringMatchPattern($value);
        $this->redisService->setStateForValue($value, $isStringOkay);

        $this->setNewGameState($isStringOkay);

        return $isStringOkay;
    }

    /**
     * @return array<GameHistory>
     */
    public function getGameHistory(): array
    {
        $cachedHistory = $this->redisService->getGameHistory();
        if ($cachedHistory !== null) {
            return json_decode($cachedHistory, true);
        }

        return $this->gameHistoryRepository->getAllSortedByDate();
    }

    /**
     * @throws ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetGame(): void
    {
        $this->redisService->resetRedis();
        $gameState = $this->gameStateRepository->findOneBy([
            'key' => GameState::GAME_STATE_KEY
        ]);

        if (!$gameState) {
            throw new ApiException('Game state not set', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->gameHistoryRepository->removeAll();

        $gameState->setState(false);
        $this->entityManager->persist($gameState);
        $this->entityManager->flush();
    }
}
