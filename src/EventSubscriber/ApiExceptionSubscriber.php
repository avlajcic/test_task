<?php

namespace App\EventSubscriber;

use App\Exception\ApiException;
use Bugsnag\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiExceptionSubscriber
 * @package App\EventSubscriber
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var string
     */
    private string $env;
    /**
     * @var Client
     */
    private Client $bugsnag;

    /**
     * {@inheritdoc}
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    /**
     * ApiExceptionSubscriber constructor.
     * @param SerializerInterface $serializer
     * @param string $env
     * @param Client $bugsnag
     */
    public function __construct(SerializerInterface $serializer, string $env, Client $bugsnag)
    {
        $this->serializer = $serializer;
        $this->env = $env;
        $this->bugsnag = $bugsnag;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        // only take care of /api URLs
        if (strpos($event->getRequest()->getPathInfo(), '/api') !== 0) {
            return;
        }

        $exception = $event->getThrowable();
        $statusCode = $exception->getCode() ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($statusCode == Response::HTTP_INTERNAL_SERVER_ERROR) {
            $this->bugsnag->notifyException($exception);
        }

        $response = new JsonResponse(
            $this->getExceptionAsArray($exception),
            $statusCode
        );
        $response->headers->set('Content-Type', 'application/json');

        $event->setResponse($response);
    }

    /**
     * @param \Throwable $throwable
     * @return array<mixed>
     */
    private function getExceptionAsArray(\Throwable $throwable): array
    {
        $exceptionArray = [
            'message' => $throwable->getMessage()
        ];

        if ($throwable instanceof ApiException) {
            $exceptionArray['errors'] = $throwable->getErrors();
        }

        if (strtolower($this->env) === 'dev') {
            $exceptionArray['line'] = $throwable->getLine();
            $exceptionArray['file'] = $throwable->getFile();
        }


        return $exceptionArray;
    }
}
