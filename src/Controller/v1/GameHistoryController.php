<?php

namespace App\Controller\v1;

use App\Controller\ApiController;
use App\Exception\ApiException;
use App\Factory\GameHistoryFactory;
use App\Service\GameHelperService;
use App\Service\RedisService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameHistoryController extends ApiController
{

    /**
     * @Route("/game-history", name="game_history", methods={"GET"})
     * @param Request $request
     * @param GameHistoryFactory $gameHistoryFactory
     * @param RedisService $redisService
     * @param GameHelperService $gameHelperService
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\ORM\ORMException
     */
    public function gameHistory(
        GameHelperService $gameHelperService
    ): Response {

        return $this->json(
            [
            'game_history' => $gameHelperService->getGameHistory(),
            'state' => $gameHelperService->getCurrentGameState()
            ],
            200,
            [],
            ['groups' => [
                'game_history:read'
            ]]
        );
    }
}
