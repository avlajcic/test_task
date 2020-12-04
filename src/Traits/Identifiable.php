<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Identifiable
 * @package App\Traits
 */
trait Identifiable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
