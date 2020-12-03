<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiControler
 * @package App\Controller
 */
abstract class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param FormInterface $form
     * @throws \Exception
     */
    protected function processForm(Request $request, FormInterface $form): void
    {
        /** @var string $content */
        $content = $request->getContent();
        $data = json_decode($content, true);

        if ($data === null) {
            throw new \Exception('Invalid JSON format sent');
        }

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }
}
