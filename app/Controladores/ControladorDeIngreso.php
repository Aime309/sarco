<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;

final readonly class ControladorDeIngreso {
  function __construct(private PDO $pdo) {
  }

  function autenticar(): void {
    $credenciales = App::request()->data->getData();

    $sentencia = $this->pdo->prepare(<<<sql
      SElECT id, clave, esta_activo FROM usuarios WHERE cedula = ?
    sql);

    $sentencia->execute([$credenciales['cedula']]);
    $usuarioEncontrado = $sentencia->fetch() ?: ['clave' => ''];

    if (!password_verify($credenciales['clave'], $usuarioEncontrado['clave'])) {
      $_SESSION['mensajes.error'] = 'Cédula o contraseña incorrecta';
      App::redirect('/');

      exit;
    } elseif (!$usuarioEncontrado['esta_activo']) {
      $_SESSION['mensajes.error'] = 'Este usuario se encuentra desactivado';
      App::redirect('/');

      exit;
    }

    if (key_exists('recordar', $credenciales)) {
      $_SESSION['usuario.recordar'] = true;
    }

    $_SESSION['usuario.id'] = $usuarioEncontrado['id'];
    App::redirect('/');
  }

  function cerrarSesion(): void {
    unset($_SESSION['usuario.id']);
    unset($_SESSION['usuario.recordar']);

    App::redirect('/');
  }
}
