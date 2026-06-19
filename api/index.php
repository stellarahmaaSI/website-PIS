<?php

// Paksa sistem membuat folder struktur cache view di /tmp Vercel sebelum index.php diload
$viewCachePath = '/tmp/framework/views';
if (!is_dir($viewCachePath)) {
    mkdir($viewCachePath, 0755, true);
}

// Jalankan aplikasi utama Laravel
require __DIR__ . '/../public/index.php';