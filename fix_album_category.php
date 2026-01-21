<?php
// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Modify the category column
\Illuminate\Support\Facades\DB::statement(
    "ALTER TABLE albums MODIFY category ENUM('Photography', 'Art', 'Music', 'Travel', 'Food', 'Fashion', 'Nature', 'Architecture', 'Sports', 'Technology', 'Pets', 'Family', 'Events', 'Business', 'Other') NULL"
);

echo "✅ Album category column updated successfully!\n";
