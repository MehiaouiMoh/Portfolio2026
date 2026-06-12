<?php
require_once __DIR__ . '/../../src/Core/Request.php';
require_once __DIR__ . '/../../src/Core/Response.php';

$request = new Request();

Response::json([
    'method'       => $request->getMethod(),
    'uri'          => $request->getUri(),
    'query_params' => $request->getQueryParams(),
    'body'         => $request->getBody(),
    'auth_header'  => $request->getHeader('Authorization'),
]);
