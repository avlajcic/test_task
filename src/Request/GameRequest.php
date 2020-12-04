<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class GameRequest
 * @package App\Request
 */
class GameRequest
{
    /**
     * @var string
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=1,
     *     max=100
     * )
     */
    public string $value;
}
