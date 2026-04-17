<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Alat;

$alats = Alat::select('id', 'nama_alat', 'images')->take(5)->get();
foreach ($alats as $a) {
    echo $a->nama_alat . ' => ' . json_encode($a->images) . PHP_EOL;
}