<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tutor;

$id = $argv[1] ?? 5;
$tutor = Tutor::with(['profile','kyc','ratings','jobs'])->find($id);
if (! $tutor) {
    echo "Tutor not found\n";
    exit(1);
}
echo json_encode($tutor->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . PHP_EOL;
