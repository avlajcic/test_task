<?php

namespace App\Exception;

/**
 * Class ApiException
 * @package App\Exception
 */
class ApiException extends \Exception
{
    private array $errors;

    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param array $errors
     */
    public function __construct(string $message, int $code = 500, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}
