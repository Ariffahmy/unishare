<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== USERS IN DATABASE ===\n";
$users = App\Models\User::select('id', 'name', 'email', 'points_balance', 'is_admin')->get();
foreach ($users as $u) {
    echo "  ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Points: {$u->points_balance} | Admin: " . ($u->is_admin ? 'Yes' : 'No') . "\n";
}
echo "\nTotal: " . $users->count() . " users\n";
