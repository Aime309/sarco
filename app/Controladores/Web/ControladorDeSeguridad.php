<?php

namespace SARCO\Controladores\Web;

final readonly class ControladorDeSeguridad {
  function respaldar(): void {
    if ($_ENV['DB_CONNECTION'] === 'mysql') {
      $backupPath = __DIR__ . '/backups/backup.mysql.sql';
      `{$_ENV['MYSQLDUMP_PATH']} --user={$_ENV['DB_USERNAME']} --password={$_ENV['DB_PASSWORD']} {$_ENV['DB_DATABASE']} > $backupPath`;
    }

    Session::set('success', 'Base de datos respaldada exitósamente');
    Flight::push('./');
  }

  function restaurar(): void {
    if ($_ENV['DB_CONNECTION'] === 'mysql') {
      $queries = explode(';', file_get_contents(__DIR__ . '/backups/backup.mysql.sql'));

      foreach ($queries as $query) {
        db()->query($query)->execute();
      }
    }

    Session::set('success', 'Base de datos restaurada exitósamente');
    Flight::push('./');
  }
}
