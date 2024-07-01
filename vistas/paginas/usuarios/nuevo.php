<?php

use flight\template\View;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;

assert($vistas instanceof View);
assert($usuario instanceof Usuario);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Registro de <?= @$_GET['rol'] === 'maestro' ? 'maestro' : 'usuario' ?>
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./usuarios" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'Debe tener mínimo 1 nombre',
    'pattern' => '(\s?[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}){1,5}',
    'name' => 'nombres',
    'placeholder' => 'Nombres',
    'minlength' => 3,
    'maxlength' => 41,
    'value' => @$_SESSION['datos']['nombres']
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'Debe tener mínimo 2 apellidos',
    'pattern' => '[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s{1}([a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s?){1,3}',
    'name' => 'apellidos',
    'placeholder' => 'Apellidos',
    'minlength' => 3,
    'maxlength' => 41,
    'value' => @$_SESSION['datos']['apellidos']
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La cédula es requerida',
    'name' => 'cedula',
    'placeholder' => 'Cédula',
    'type' => 'number',
    'min' => 1000000,
    'max' => 99999999,
    'value' => @$_SESSION['datos']['cedula']
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La contraseña es requerida',
    'name' => 'clave',
    'placeholder' => 'Contraseña',
    'type' => 'password',
    'pattern' => '(.+){8,}',
    'minlength' => 8,
    'value' => @$_SESSION['datos']['clave']
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El rol es requerido',
    'name' => 'rol',
    'placeholder' => 'Rol',
    'value' => @$_SESSION['datos']['rol'],
    'opciones' => array_map(static fn (Rol $rol): array => [
      'value' => $rol->name,
      'children' => $rol->name,
      'selected' => @$_GET['rol'] === 'maestro'
    ], Rol::menoresQue($usuario->rol))
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La fecha de nacimiento es requerida',
    'name' => 'fecha_nacimiento',
    'placeholder' => 'Fecha de nacimiento',
    'type' => 'date',
    'value' => @$_SESSION['datos']['fecha_nacimiento'],
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El género es requerido',
    'name' => 'genero',
    'placeholder' => 'Género',
    'value' => @$_SESSION['datos']['genero'],
    'opciones' => array_map(static fn (Genero $genero): array => [
      'value' => $genero->name,
      'children' => $genero->name
    ], Genero::cases())
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'La dirección es requerida',
    'name' => 'direccion',
    'placeholder' => 'Dirección',
    'minlength' => 3,
    'value' => @$_SESSION['datos']['direccion'],
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
    'type' => 'tel',
    'name' => 'telefono',
    'placeholder' => 'Teléfono',
    'minlength' => 15,
    'maxlength' => 15,
    'pattern' => '\+\d{2} \d{3}-\d{7}',
    'value' => @$_SESSION['datos']['telefono'],
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El correo es requerido',
    'type' => 'email',
    'name' => 'correo',
    'placeholder' => 'Correo',
    'minlength' => 5,
    'value' => @$_SESSION['datos']['correo'],
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar',
  ]);

  ?>
</form>
