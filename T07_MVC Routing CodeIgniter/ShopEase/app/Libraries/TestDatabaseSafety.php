<?php

namespace App\Libraries;

final class TestDatabaseSafety
{
    public static function isSafe(string $testName, string $defaultName): bool
    {
        $testName = strtolower(trim($testName));
        $defaultName = strtolower(trim($defaultName));

        return $testName !== '' && $testName !== $defaultName
            && (str_starts_with($testName, 'test_') || str_ends_with($testName, '_test'));
    }
}
