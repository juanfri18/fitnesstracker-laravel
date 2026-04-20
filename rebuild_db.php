<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
Illuminate\Support\Facades\Schema::dropIfExists('logro_user');
Illuminate\Support\Facades\Schema::dropIfExists('logros');
Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%logro%')->delete();
Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

try {
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "MIGRATE OUT:\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
    Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'LogroSeeder', '--force' => true]);
    echo "SEED OUT:\n" . Illuminate\Support\Facades\Artisan::output() . "\n";
    echo "\nTODO OK";
} catch (\Throwable $e) {
    echo "ERROR FINAL: " . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine();
}
