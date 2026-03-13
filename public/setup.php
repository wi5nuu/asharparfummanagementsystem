<?php

/**
 * APMS Shared Hosting Path Fixer
 * This script helps link the public folder content with the Laravel core
 * when they are separated in high-security shared hosting environments.
 */

$publicPath = __DIR__;
$laravelCorePath = __DIR__ . '/../laravel_core'; // Adjust this to your folder name

if (!file_exists($laravelCorePath . '/vendor/autoload.php')) {
    die("Error: Laravel Core not found at $laravelCorePath. Please check the folder name.");
}

// 1. Fix index.php paths
$indexPath = $publicPath . '/index.php';
if (file_exists($indexPath)) {
    $content = file_get_contents($indexPath);
    $content = str_replace("__DIR__.'/../vendor/autoload.php'", "__DIR__.'/../laravel_core/vendor/autoload.php'", $content);
    $content = str_replace("__DIR__.'/../bootstrap/app.php'", "__DIR__.'/../laravel_core/bootstrap/app.php'", $content);
    file_put_contents($indexPath, $content);
    echo "✅ index.php paths updated.<br>";
}

// 2. Create Storage Symlink
$target = $laravelCorePath . '/storage/app/public';
$link = $publicPath . '/storage';

if (file_exists($link)) {
    echo "⚠️ Storage link already exists.<br>";
} else {
    if (symlink($target, $link)) {
        echo "✅ Storage symlink created successfully.<br>";
    } else {
        echo "❌ Failed to create storage symlink. You might need to do this manually or contact support.<br>";
    }
}

echo "<h3>Setup Complete!</h3>";
echo "You can now delete this file (setup.php) for security.";
