<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$roleId = Role::where('slug', 'super-admin')->value('id');
if (! $roleId) {
    echo "ROLE_NOT_FOUND\n";
    exit(1);
}

// Change these values if needed
$email = 'sadmin@gmail.com';
$passwordPlain = 'password';
$name = 'Super Admin Temp';

// Avoid duplicate user - update if exists
$existing = User::where('email', $email)->first();
if ($existing) {
    echo "ALREADY_EXISTS:" . $existing->id . "\n";
    exit(0);
}

$user = User::create([
    'name' => $name,
    'email' => $email,
    'password' => Hash::make($passwordPlain),
    'role_id' => $roleId,
    'is_active' => true,
]);

if ($user) {
    echo "CREATED:" . $user->id . "\n";
    exit(0);
}

echo "FAILED\n";
exit(1);
