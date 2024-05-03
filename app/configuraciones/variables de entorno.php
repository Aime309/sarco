<?php

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv;

if (!file_exists(ROOT . '/.env')) {
  copy(ROOT . '/.env.dist', ROOT . '/.env');
}

$dotenv->load(ROOT . '/.env');
$_ENV['DB_DATABASE'] = str_replace('%s', dirname(__DIR__, 2), $_ENV['DB_DATABASE']);
$_ENV['IS_DEBUG'] = strtolower($_ENV['IS_DEBUG']) === 'true';
