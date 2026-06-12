<?php
require_once __DIR__ . '/../../src/Core/Response.php';

Response::success('Opération réussie', ['id' => 123, 'name' => 'Test']);
