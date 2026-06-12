<?php
require_once __DIR__ . '/../../src/Core/Response.php';

Response::json(['message' => 'Hello World', 'version' => '1.0']);
