<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Service\ConfigService;

$config = new ConfigService();

$dbHost     = $config->get('database_host');
$dbName     = $config->get('database_name');
$dbPort     = $config->get('database_port');
$dbUser     = $config->get('database_user');
$dbPassword = $config->get('database_password');

$sql = file_get_contents(__DIR__ . '/queries.sql');

$pdo = new \PDO("mysql:host=$dbHost;port=$dbPort", $dbUser, $dbPassword);
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$pdo->query("CREATE DATABASE IF NOT EXISTS $dbName");
$pdo->query("USE $dbName");
$pdo->query("SET NAMES utf8");
$pdo->exec($sql);

echo 'Done!';