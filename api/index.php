<?php
// 1. Aktifkan Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Paksa Laravel menulis ke folder /tmp (Sangat Penting di Vercel!)
// Karena folder storage asli di Vercel bersifat Read-Only
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

// Buat folder temporary jika belum ada
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

require __DIR__ . '/../public/index.php';