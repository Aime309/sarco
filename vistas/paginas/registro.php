<?php

use flight\template\View;
use SARCO\Enumeraciones\Genero;

assert($vistas instanceof View);
scripts('recursos/js/registro.js');

?>

<form autocomplete="off" method="post" class="form form--scrollable form--full form--with-validation">
  <div class="form__body">
    <h1 class="form__title">Cree su cuenta de director/a</h1>
    <?php

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'nombres',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'apellidos',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'La cédula es requerida',
        'name' => 'cedula',
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'La contraseña es requerida',
        'name' => 'clave',
        'placeholder' => 'Contraseña',
        'type' => 'password',
        'pattern' => '(.+){8,}',
        'minlength' => 8
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'fecha_nacimiento',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date'
      ]
    );

    echo $vistas->fetch(
      'componentes/Select',
      [
        'validacion' => 'El género es requerido',
        'name' => 'genero',
        'placeholder' => 'Género',
        'opciones' => array_map(static fn (Genero $genero): array => [
          'value' => $genero->name,
          'children' => $genero->name
        ], Genero::cases())
      ]
    );

    echo $vistas->fetch(
      'componentes/Textarea',
      [
        'validacion' => 'La dirección es requerida',
        'name' => 'direccion',
        'placeholder' => 'Dirección',
        'minlength' => 3
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
        'type' => 'tel',
        'name' => 'telefono',
        'placeholder' => 'Teléfono',
        'minlength' => 15,
        'maxlength' => 15,
        'pattern' => '\+\d{2} \d{3}-\d{7}'
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'correo',
        'placeholder' => 'Correo',
        'minlength' => 5
      ]
    );

    ?>
    <button class="button button--spaced">Registrarse</button>
  </div>
  <div class="form__background" style="background-image: url(recursos/imagenes/OIG1.jpeg)"></div>
</form>
