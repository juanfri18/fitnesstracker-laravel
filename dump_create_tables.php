<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schemas = [];
foreach(['usuarios', 'entrenamientos', 'objetivos'] as $t) {
    try {
        $res = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE $t");
        $schemas[$t] = $res[0]->{'Create Table'};
    } catch (\Exception $e) {
        $schemas[$t] = "Does not exist: " . $e->getMessage();
    }
}
file_put_contents('create_tables_dump.json', json_encode($schemas, JSON_PRETTY_PRINT));
