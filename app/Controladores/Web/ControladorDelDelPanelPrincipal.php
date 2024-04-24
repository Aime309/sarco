<?php

namespace SARCO\Controladores\Web;

use PDO;

final readonly class ControladorDelDelPanelPrincipal {
  function __construct(private PDO $conexion) {
  }

  function mostrarPaginaDeInicio(): void {
    $cantidadDeUsuarios = $this
      ->conexion
      ->query('SELECT COUNT(id) FROM usuarios')
      ->fetchColumn();

    $cantidadDeRepresentantes = $this
      ->conexion
      ->query('SELECT COUNT(id) FROM representantes')
      ->fetchColumn();

    renderizar(
      'inicio',
      'Inicio',
      'principal',
      compact('cantidadDeUsuarios', 'cantidadDeRepresentantes')
    );
  }
}
