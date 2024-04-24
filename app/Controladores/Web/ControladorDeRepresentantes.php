<?php

namespace SARCO\Controladores\Web;

use PDO;

final readonly class ControladorDeRepresentantes {
  function __construct(private PDO $conexion) {
  }

  function mostrarListado(): void {
    $representantes = /*array_map(function (array $representante): Representante {
      return new Representante(
        $representante['id'],
        $representante['cedula'],
        $representante['nombres'],
        $representante['apellidos'],
        Sexo::from($representante['sexo']),
        new DateTimeImmutable($representante['fecha_nacimiento']),
        $representante['telefono'],
        $representante['correo'],
        $representante['direccion'],
        new DateTimeImmutable($representante['fecha_registro'])
      );
    }, db()->select('representantes')->all());*/ [];

    renderizar(
      'listado-representantes',
      'Representantes',
      'principal',
      compact('representantes')
    );
  }

  function mostrarRegistro(): void {
    renderizar('nuevo-representante', 'Nuevo representante', 'principal');
  }

  function procesarRegistro(): void {
    $info = Request::body();

    db()->insert('representantes')->params([
      'cedula' => $info['cedula'],
      'nombres' => $info['fullname'],
      'apellidos' => $info['lastname'],
      'sexo' => $info['sexo'],
      'fecha_nacimiento' => $info['dob'],
      'telefono' => $info['phone'],
      'correo' => $info['email'],
      'direccion' => $info['address']
    ])->execute();

    Session::set('success', 'Representante registrado exitósamente');
    Flight::push('./');
  }

  function mostrarEdicion(int $cedula): void {
    $info = db()->select('representantes')->where('cedula', $cedula)->assoc();

    renderizar('editar-representante', 'Editar representante', 'principal', ['representante' => new Representante(
      $info['id'],
      $info['cedula'],
      $info['nombres'],
      $info['apellidos'],
      Sexo::from($info['sexo']),
      new DateTimeImmutable($info['fecha_nacimiento']),
      $info['telefono'],
      $info['correo'],
      $info['direccion'],
      new DateTimeImmutable($info['fecha_registro'])
    )]);
  }

  function procesarEdicion(int $cedula): void {
    $info = Request::body();

    db()->update('representantes')
      ->params([
        'nombres' => $info['fullname'],
        'apellidos' => $info['lastname'],
        'cedula' => $info['cedula'],
        'sexo' => $info['sexo'],
        'fecha_nacimiento' => $info['dob'],
        'telefono' => $info['phone'],
        'correo' => $info['email'],
        'direccion' => $info['address']
      ])
      ->execute();

    Session::set('success', 'Representante editado exitósamente');
    Flight::push('../');
  }
}
