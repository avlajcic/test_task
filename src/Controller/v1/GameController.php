<?php

namespace App\Controller\v1;

use App\Controller\ApiController;
use App\Exception\ApiException;
use App\Factory\GameHistoryFactory;
use App\Form\GameRequestType;
use App\Request\GameRequest;
use App\Service\GameHelperService;
use App\Service\RedisService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GameController extends ApiController
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * GameController constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @Route("/game", name="v1_game")
     * @param Request $request
     * @param GameHistoryFactory $gameHistoryFactory
     * @param RedisService $redisService
     * @param GameHelperService $gameHelperService
     * @return Response
     * @throws ApiException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function game(
        Request $request,
        GameHistoryFactory $gameHistoryFactory,
        RedisService $redisService,
        GameHelperService $gameHelperService
    ): Response {

        if ($gameHelperService->getCurrentGameState() === true) {
            throw new ApiException('Game is finished', Response::HTTP_FORBIDDEN);
        }

        $gameRequest = new GameRequest();
        $form = $this->createForm(
            GameRequestType::class,
            $gameRequest
        );

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwFormValidationException($form);
        }

        $gameHistory = $gameHistoryFactory->createFromGameRequest($gameRequest);
        $redisService->saveNewGameHistory($gameHistory);

        $isStringOkay = $gameHelperService->getStateForValue($gameRequest->value);

        return $this->json([
            'ok' => $isStringOkay,
        ]);
    }
}
