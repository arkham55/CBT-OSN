<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Question::orderBy('id', 'desc')->take(3)->get() as $q) {
    echo $q->id . ' - Correct: ' . $q->correct_option . ' - Expl: ' . $q->explanation . PHP_EOL;
}
