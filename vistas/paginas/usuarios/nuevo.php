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
    Registro de usuario
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./usuarios" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'Los nombres sólo pueden contener letras',
    'name' => 'nombres',
    'placeholder' => 'Nombres',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'Los apellidos sólo pueden contener letras',
    'name' => 'apellidos',
    'placeholder' => 'Apellidos',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La cédula es requerida',
    'name' => 'cedula',
    'placeholder' => 'Cédula',
    'type' => 'number',
    'min' => 1000000,
    'max' => 99999999
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La contraseña es requerida',
    'name' => 'clave',
    'placeholder' => 'Contraseña',
    'type' => 'password',
    'pattern' => '(.+){8,}',
    'minlength' => 8
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El rol es requerido',
    'name' => 'rol',
    'placeholder' => 'Rol',
    'opciones' => array_map(static fn (Rol $rol): array => [
      'value' => $rol->name,
      'children' => $rol->name
    ], Rol::menoresQue($usuario->rol))
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La fecha de nacimiento es requerida',
    'name' => 'fecha_nacimiento',
    'placeholder' => 'Fecha de nacimiento',
    'type' => 'date'
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El género es requerido',
    'name' => 'genero',
    'placeholder' => 'Género',
    'opciones' => array_map(static fn (Genero $genero): array => [
      'value' => $genero->name,
      'children' => $genero->name
    ], Genero::cases())
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'La dirección es requerida',
    'name' => 'direccion',
    'placeholder' => 'Dirección',
    'minlength' => 3
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
    'type' => 'tel',
    'name' => 'telefono',
    'placeholder' => 'Teléfono',
    'minlength' => 15,
    'maxlength' => 15,
    'pattern' => '\+\d{2} \d{3}-\d{7}'
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El correo es requerido',
    'type' => 'email',
    'name' => 'correo',
    'placeholder' => 'Correo',
    'minlength' => 5
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>
