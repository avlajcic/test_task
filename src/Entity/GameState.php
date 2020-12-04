<?php

namespace App\Entity;

use App\Repository\GameStateRepository;
use App\Traits\Identifiable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameStateRepository::class)
 */
class GameState
{
    use Identifiable;

    public const GAME_STATE_KEY = 'game_state';

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $key;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private bool $state;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getState(): bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
