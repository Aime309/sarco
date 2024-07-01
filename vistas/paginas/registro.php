<?php

use flight\template\View;
use SARCO\Enumeraciones\Genero;

assert($vistas instanceof View);

?>

<form method="post" class="form form--scrollable form--full form--with-validation">
  <div class="form__body">
    <h1 class="form__title">Cree su cuenta de director/a</h1>
    <?php

    $vistas->render('componentes/Input', [
      'validacion' => 'Debe tener mínimo 1 nombre',
      'pattern' => '(\s?[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}){1,5}',
      'name' => 'nombres',
      'placeholder' => 'Nombres',
      'minlength' => 3,
      'maxlength' => 40,
      'value' => $_SESSION['datos']['nombres'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'Debe tener mínimo 2 apellidos',
      'pattern' => '[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s{1}([a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s?){1,3}',
      'name' => 'apellidos',
      'placeholder' => 'Apellidos',
      'minlength' => 3,
      'maxlength' => 40,
      'value' => $_SESSION['datos']['apellidos'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'La cédula es requerida',
      'name' => 'cedula',
      'placeholder' => 'Cédula',
      'type' => 'number',
      'min' => 1_000_000,
      'max' => 31_000_000,
      'value' => $_SESSION['datos']['cedula'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'La contraseña debe tener al menos 1 mayúscula,
        1 número y un símbolo',
      'name' => 'clave',
      'placeholder' => 'Contraseña',
      'type' => 'password',
      'pattern' => '(?=.*\d)(?=.*[A-ZÑ])(?=.*\W).{8,}',
      'minlength' => 8,
      'value' => $_SESSION['datos']['clave'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'La fecha de nacimiento es requerida',
      'name' => 'fecha_nacimiento',
      'placeholder' => 'Fecha de nacimiento',
      'type' => 'date',
      'value' => $_SESSION['datos']['fecha_nacimiento'] ?? ''
    ]);

    $vistas->render('componentes/Select', [
      'validacion' => 'El género es requerido',
      'name' => 'genero',
      'placeholder' => 'Género',
      'opciones' => array_map(static fn(Genero $genero): array => [
        'value' => $genero->name,
        'children' => $genero->name,
        'selected' => @$_SESSION['datos']['genero'] === $genero->name
      ], Genero::cases())
    ]);

    $vistas->render('componentes/Textarea', [
      'validacion' => 'La dirección es requerida',
      'name' => 'direccion',
      'placeholder' => 'Dirección',
      'minlength' => 3,
      'value' => $_SESSION['datos']['direccion'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'El teléfono no es válido: +XX XXX-XXXXXXX',
      'type' => 'tel',
      'name' => 'telefono',
      'placeholder' => 'Teléfono',
      'minlength' => 15,
      'maxlength' => 15,
      'pattern' => '\+\d{2} \d{3}-\d{7}',
      'value' => $_SESSION['datos']['telefono'] ?? ''
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'El correo es requerido',
      'type' => 'email',
      'name' => 'correo',
      'placeholder' => 'Correo',
      'minlength' => 5,
      'value' => $_SESSION['datos']['correo'] ?? ''
    ]);

    ?>
    <button class="button button--spaced">Registrarse</button>
  </div>
  <div class="form__background" style="background-image: url(recursos/imagenes/OIG1.jpeg)"></div>
</form>
