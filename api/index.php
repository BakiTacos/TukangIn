<?php

// 1. Aktifkan pelaporan error agar tidak layar putih jika terjadi crash
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Paksa Laravel menggunakan folder /tmp untuk menyimpan cache & views
// Ini krusial karena folder storage asli di Vercel bersifat Read-Only
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

// Membuat direktori temporary jika belum ada
if (!is_dir('/tmp/storage/framework/views')) {
    mkdir('/tmp/storage/framework/views', 0755, true);
}

// 3. Panggil file public/index.php asli
// Cara ini lebih aman daripada menulis ulang logika kernel di sini
require __DIR__ . '/../public/index.php';