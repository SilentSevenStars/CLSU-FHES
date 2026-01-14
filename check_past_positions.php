<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Position;

$count = Position::whereRaw("STR_TO_DATE(end_date, '%Y-%m-%d') < CURDATE() AND end_date IS NOT NULL")->count();
echo "Positions with past end_date: $count\n";

$positions = Position::whereRaw("STR_TO_DATE(end_date, '%Y-%m-%d') < CURDATE() AND end_date IS NOT NULL")->get();
foreach ($positions as $pos) {
    echo "ID: {$pos->id}, Name: {$pos->name}, End Date: {$pos->end_date}\n";
}