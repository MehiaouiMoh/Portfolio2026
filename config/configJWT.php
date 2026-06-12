<?php
// config.php
function getEnvValue($key) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($envKey, $envValue) = explode('=', $line, 2);
        if ($envKey === $key) {
            return trim($envValue);
        }
    }
    return null;
}

define('JWT_SECRET', getEnvValue('JWT_SECRET'));
