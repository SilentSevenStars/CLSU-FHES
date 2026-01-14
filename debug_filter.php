<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Position;

$filtered = Position::where(function($q) {
    $q->whereNull('end_date')
      ->orWhere('end_date', '>=', now()->toDateString());
})->get();

echo "Filtered positions count: " . $filtered->count() . "\n";
foreach ($filtered as $pos) {
    echo "ID: {$pos->id}, Name: {$pos->name}, End Date: {$pos->end_date}\n";
}

echo "\nCurrent date: " . now()->toDateString() . "\n";

$past = Position::whereNotNull('end_date')->where('end_date', '<', now()->toDateString())->get();
echo "Past positions count: " . $past->count() . "\n";
foreach ($past as $pos) {
    echo "ID: {$pos->id}, Name: {$pos->name}, End Date: {$pos->end_date}\n";
}