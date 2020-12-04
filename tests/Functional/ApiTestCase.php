<?php

namespace App\Tests\Functional;

use App\Kernel;
use App\Service\RedisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiTestCase
 * @package App\Tests\Functional
 */
class ApiTestCase extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected KernelBrowser $client;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return Kernel::class;
    }

    /**
     *
     */
    protected function tearDown(): void
    {
        self::$container->get('redis_service')->resetRedis();

        parent::tearDown();

        static::$class = null;
    }

    /**
     *
     */
    public function setUp(): void
    {
        $this->client = self::createClient();

        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
        $this->client->setServerParameter('HTTP_ACCEPT_ENCODING', 'identity');
        $this->client->setServerParameter('HTTP_ACCEPT_CHARSET', 'utf-8');
        $this->client->setServerParameter('HTTP_ACCEPT_LANGUAGE', 'en');

        $this->entityManager = self::$container
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $content
     * @return Response
     */
    protected function request(
        string $method,
        string $uri,
        array $content = []
    ): Response {

        $this->client->request(
            $method,
            $uri,
            [],
            [],
            [],
            json_encode($content)
        );

        $response = $this->client->getResponse();
        $this->client->restart();
        return $response;
    }
}
