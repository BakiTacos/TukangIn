<?php
// Pastikan path ini benar-benar mengarah ke folder vendor
require __DIR__ . '/../vendor/autoload.php';

// Pastikan path ini mengarah ke file bootstrap app
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);