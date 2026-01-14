<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Position;

$all = Position::all();
echo "All positions:\n";
foreach ($all as $pos) {
    echo "ID: {$pos->id}, Name: {$pos->name}, End Date: {$pos->end_date}\n";
}

echo "\nCurrent date: " . now()->toDateString() . "\n";

$filtered = Position::query()
    ->where(function($query) {
        $query->whereNull('end_date')
              ->orWhere('end_date', '>=', now()->toDateString());
    })
    ->get();

echo "\nFiltered positions:\n";
foreach ($filtered as $pos) {
    echo "ID: {$pos->id}, Name: {$pos->name}, End Date: {$pos->end_date}\n";
}