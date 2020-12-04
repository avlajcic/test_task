<?php

namespace App\Service;

use App\Entity\GameHistory;
use Predis\Client;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RedisService
 * @package App\Service
 */
class RedisService
{
    const GAME_HISTORY_KEY = 'game:history';
    const GAME_STATE_KEY = 'game:state';
    const GAME_VALUE_KEY_PREFIX = 'game:value:';

    /**
     * @var Client
     */
    private Client $redis;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * RedisService constructor.
     * @param Client $redis
     * @param SerializerInterface $serializer
     */
    public function __construct(Client $redis, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function saveNewGameHistory(GameHistory $gameHistory): void
    {
        $gameHistoryNormalized = $this->serializer->normalize(
            $gameHistory,
            null,
            [
                'groups'=> ["game_history:read"]
            ]
        );

        if ($this->redis->exists(self::GAME_HISTORY_KEY)) {
            $historyContent = json_decode($this->redis->get(self::GAME_HISTORY_KEY), true);
            array_unshift($historyContent, $gameHistoryNormalized);
        } else {
            $historyContent = [$gameHistoryNormalized];
        }

        $this->redis->set(self::GAME_HISTORY_KEY, json_encode($historyContent));
    }

    public function saveNewGameState(bool $isGameFinished): void
    {
        $this->redis->set(self::GAME_STATE_KEY, $isGameFinished ? 'true' : 'false');
    }

    public function getGameState(): ?bool
    {
        if ($this->redis->exists(self::GAME_STATE_KEY)) {
            return $this->redis->get(self::GAME_STATE_KEY) === 'true';
        }

        return null;
    }

    public function setStateForValue(string $value, bool $state): void
    {
        $redisGameValueKey = self::GAME_VALUE_KEY_PREFIX . $value;

        $this->redis->set($redisGameValueKey, $state ? 'true' : 'false');
    }

    public function getStateForValue(string $value): ?bool
    {
        $redisGameValueKey = self::GAME_VALUE_KEY_PREFIX . $value;
        if ($this->redis->exists($redisGameValueKey)) {
            return $this->redis->get($redisGameValueKey) === 'true';
        }

        return null;
    }
}
