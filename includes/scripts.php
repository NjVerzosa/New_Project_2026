<?php
// Your updated secure script
session_start();
require_once 'includes/pdo.php';
require_once 'env.loader.php';

header('Content-Type: application/json');

// Use environment variables for configuration
$rateLimitEnabled = EnvironmentLoader::get('RATE_LIMIT_ENABLED', true);
$maxScore = EnvironmentLoader::get('MAX_SCORE', 1000000);

// Rest of your script using EnvironmentLoader::get() for configuration...