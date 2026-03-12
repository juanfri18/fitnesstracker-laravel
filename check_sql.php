<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$log = file_get_contents(storage_path('logs/laravel.log'));
preg_match_all("/General error: 1005 Can't create table.*?\(Connection: (mysql|sqlite), SQL: (.*?)\)/s", $log, $matches);
if (!empty($matches[2])) {
    echo "LATEST SQL ERROR:\n";
    echo end($matches[2]) . "\n";
} else {
    echo "NO RECENT ERRORS FOUND IN LOG\n";
}
