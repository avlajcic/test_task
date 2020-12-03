<?php

namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Identifiable
 * @package App\Traits
 */
trait Timestampable
{
    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected DateTime $createdAt;

    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
