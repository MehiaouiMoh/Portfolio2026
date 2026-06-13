<?php
// Rediriger les erreurs PHP natives vers notre fichier de log
$logFile = __DIR__ . '/../logs/errors.log';
ini_set('log_errors', 1);
ini_set('error_log', $logFile);
error_reporting(E_ALL);

// Attraper les exceptions non gérées (ex: die() remplacé par throw)
set_exception_handler(function (Throwable $e) use ($logFile) {
    $timestamp = date('Y-m-d H:i:s');
    $method    = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
    $uri       = $_SERVER['REQUEST_URI']    ?? '-';
    $ip        = $_SERVER['REMOTE_ADDR']    ?? '-';
    error_log("[$timestamp] [FATAL] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . " | $method $uri | IP: $ip" . PHP_EOL, 3, $logFile);

    if (!headers_sent()) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur serveur interne']);
    }
    exit();
});

// Attraper les erreurs fatales PHP (parse error, out of memory, etc.)
register_shutdown_function(function () use ($logFile) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $timestamp = date('Y-m-d H:i:s');
        $method    = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $uri       = $_SERVER['REQUEST_URI']    ?? '-';
        error_log("[$timestamp] [FATAL] " . $error['message'] . " in " . $error['file'] . ":" . $error['line'] . " | $method $uri" . PHP_EOL, 3, $logFile);

        if (!headers_sent()) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur interne']);
        }
    }
});

require_once __DIR__.'/../src/Core/Router.php';
$router = new Router();

require_once __DIR__ . '/routes.php';

$request = new Request();
$router->dispatch($request);
