<?php
// One-off script to list suspended users and their status_reason
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$suspended = User::where('status', 'suspended')->get();
if ($suspended->count() === 0) {
    echo "No suspended users found.\n";
    exit(0);
}

foreach ($suspended as $u) {
    echo "ID: {$u->id}\n";
    echo "Name: {$u->name}\n";
    echo "Email: {$u->email}\n";
    echo "Status Reason: " . ($u->status_reason ?? '[none]') . "\n";
    echo "Updated At: " . ($u->status_updated_at ? $u->status_updated_at->toDateTimeString() : '[unknown]') . "\n";
    echo str_repeat('-', 40) . "\n";
}
