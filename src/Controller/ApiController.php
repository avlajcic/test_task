<?php

namespace App\Controller;

use App\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiControler
 * @package App\Controller
 */
abstract class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param FormInterface<Form> $form
     * @throws \Exception
     */
    public function processForm(Request $request, FormInterface $form): void
    {
        /** @var string $content */
        $content = $request->getContent();
        $data = json_decode($content, true);

        if ($data === null) {
            throw new \Exception('Invalid JSON format sent', Response::HTTP_BAD_REQUEST);
        }

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }

    /**
     * @param FormInterface<Form> $form
     * @throws ApiException
     */
    public function throwFormValidationException(FormInterface $form): void
    {
        $errors = $this->getErrorsFromForm($form);

        throw new ApiException('Form validation failed', Response::HTTP_BAD_REQUEST, $errors);
    }

    /**
     * @param Request $request
     * @return array<object>
     * @throws \Exception
     */
    public function getDataFromRequest(Request $request): array
    {
        /** @var string $content */
        $content = $request->getContent();
        $data = json_decode($content, true);

        if ($data === null) {
            throw new \Exception('Invalid JSON format sent');
        }

        return $data;
    }

    /**
     * @param FormInterface<Form> $form
     * @return array<mixed>.
     */
    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();

        /** @var FormError $error */
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
