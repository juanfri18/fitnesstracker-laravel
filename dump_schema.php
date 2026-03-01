<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schema = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM objetivos");
file_put_contents('schema_dump.json', json_encode($schema, JSON_PRETTY_PRINT));
