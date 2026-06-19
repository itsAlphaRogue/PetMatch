<?php

$hostname = "localhost";
$username = "root";
$password = "itsme";
$db = "petmatch";

$con = mysqli_connect($hostname, $username, $password, $db);

// Simple PSR-4 style Autoloader for PetMatch namespace
spl_autoload_register(function ($class) {
    $prefix = 'PetMatch\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});