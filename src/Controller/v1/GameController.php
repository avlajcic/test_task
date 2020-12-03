<?php

namespace App\Controller\v1;

use App\Controller\ApiController;
use App\Form\GameRequestType;
use App\Request\GameRequest;
use App\Service\StringCheckerService;
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
     * @param StringCheckerService $stringCheckerService
     * @return Response
     * @throws \Exception
     */
    public function game(Request $request, StringCheckerService $stringCheckerService): Response
    {
        $gameRequest = new GameRequest();
        $form = $this->createForm(
            GameRequestType::class,
            $gameRequest
        );

        $this->processForm($request, $form);

        if (!$form->isValid()) {
            throw new \Exception('Invalid data sent');
        }

        $isStringOkay = $stringCheckerService->doesStringMatchPattern($gameRequest->value);

        return $this->json([
            'ok' => $isStringOkay,
        ]);
    }
}
