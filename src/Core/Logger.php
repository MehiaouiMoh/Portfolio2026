<?php

class Logger
{
    private static string $logFile = __DIR__ . '/../../logs/errors.log';

    public static function error(string $message, int $statusCode = 0): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $method    = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $uri       = $_SERVER['REQUEST_URI']    ?? '-';
        $ip        = $_SERVER['REMOTE_ADDR']    ?? '-';

        // Remonte la pile : frame 0 = Logger, frame 1 = Response, frame 2 = Controller
        $trace    = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller   = $trace[2] ?? $trace[1] ?? null;
        $location = $caller ? basename($caller['file']) . ':' . $caller['line'] : 'unknown';

        $line = "[$timestamp] [ERROR] [$statusCode] $message | $method $uri | IP: $ip | From: $location" . PHP_EOL;

        error_log($line, 3, self::$logFile);
    }
}
