<?php

namespace App\Entity;

use App\Repository\GameHistoryRepository;
use App\Traits\Identifiable;
use App\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameHistoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class GameHistory
{
    use Identifiable;
    use Timestampable;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private string $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
