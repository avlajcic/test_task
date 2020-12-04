<?php

namespace App\Controller\v1;

use App\Controller\ApiController;
use App\Exception\ApiException;
use App\Service\GameHelperService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameHistoryController extends ApiController
{

    /**
     * @Route("/game-history", name="v1_game_history", methods={"GET"})
     * @param GameHelperService $gameHelperService
     * @return Response
     * @throws ApiException
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
