<?php

/** Obtiene una conexión a la `Base de Datos` */
function bd(): PDO {
  static $conexion = null;

  if (!$conexion) {
    $dsn = match (strtolower($_ENV['DB_CONNECTION'])) {
      'sqlite' => 'sqlite:' . $_ENV['DB_DATABASE'],
      'mysql' => "mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_NAME']}; charset=utf; port={$_ENV['DB_PORT']}"
    };

    $conexion = new PDO($dsn, @$_ENV['DB_USERNAME'], @$_ENV['DB_PASSWORD'], [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }

  return $conexion;
}
