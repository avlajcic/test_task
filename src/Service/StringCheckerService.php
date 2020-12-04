<?php

namespace App\Service;

/**
 * Class StringCheckerService
 * @package App\Service
 */
class StringCheckerService
{
    /**
     * @var string[]
     */
    private static array $patternLetters = ['a', 'e', 'i', 'o', 'u'];

    public function doesStringMatchPattern(string $value): bool
    {
        $value = strtolower($value);
        $length = strlen($value);

        if (!in_array($value[0], self::$patternLetters)) {
            return $value[$length - 1] === '!';
        }

        for ($i = 1; $i < $length; $i++) {
            if ($value[$i] === 'b') {
                $value = str_replace('#', '', $value);
                return substr($value, $i, 8) === 'baguette';
            }
            if (!in_array($value[$i], self::$patternLetters)) {
                return false;
            }
        }

        return true;
    }
}
