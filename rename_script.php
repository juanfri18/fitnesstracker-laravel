<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('entrenamientos', function (Blueprint $table) {
    $table->dropForeign(['user_id']); // or whatever the column is. "user_id" probably. Let's check migrations.
});
