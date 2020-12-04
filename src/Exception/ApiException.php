<?php

namespace App\Exception;

/**
 * Class ApiException
 * @package App\Exception
 */
class ApiException extends \Exception
{
    /**
     * @var array<mixed>
     */
    private array $errors;

    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param array<mixed> $errors
     */
    public function __construct(string $message, int $code = 500, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    /**
     * @return array<mixed>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
