<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;

final readonly class ControladorDeRespaldos {
  function __construct(private PDO $pdo) {
  }

  function respaldar(): void {
    if (strtolower($_ENV['DB_CONNECTION']) === 'mysql') {
      $backupPath = dirname(__DIR__) . '/base de datos/backups/backup.mysql.sql';

      `{$_ENV['MYSQLDUMP_PATH']} --user={$_ENV['DB_USERNAME']} --password={$_ENV['DB_PASSWORD']} {$_ENV['DB_DATABASE']} > '$backupPath'`;
    } elseif (strtolower($_ENV['DB_CONNECTION']) === 'sqlite') {
      copy($_ENV['DB_DATABASE'], $_ENV['DB_DATABASE'] . '.backup');
    }

    $_SESSION['mensajes.exito'] = 'Base de datos respaldada exitósamente';
    App::redirect('/');
  }

  function restaurar(): void {
    if (strtolower($_ENV['DB_CONNECTION']) === 'mysql') {
      $queries = explode(
        ';',
        file_get_contents(__DIR__ . '/../base de datos/backups/backup.mysql.sql')
      );

      foreach ($queries as $query) {
        $this->pdo->query($query);
      }
    } elseif (strtolower($_ENV['DB_CONNECTION']) === 'sqlite') {
      // TODO: limpiar todas las instancias de PDO para eliminar el archivo
      unlink($_ENV['DB_DATABASE']);
      rename($_ENV['DB_DATABASE'] . '.backup', $_ENV['DB_DATABASE']);
    }

    $_SESSION['mensajes.exito'] = 'Base de datos restaurada exitósamente';
    App::redirect('/');
  }
}
