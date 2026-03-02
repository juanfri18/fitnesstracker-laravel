<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schema_users = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM users");
file_put_contents('users_schema.json', json_encode($schema_users, JSON_PRETTY_PRINT));

$schema_usuarios = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM usuarios");
file_put_contents('usuarios_schema.json', json_encode($schema_usuarios, JSON_PRETTY_PRINT));
