<?php

use Leaf\BareUI;
use SARCO\Modelos\Rol;
use SARCOV2\Compartido\Dominio\Genero;

/** @var BareUI $template */

$scripts('assets/js/registro.js')

?>

<form action="registrate" method="post" class="form form--full form--with-validation">
  <div class="form__body">
    <h1 class="form__title">Cree su cuenta de director/a</h1>
    <?php

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'Los nombres son requeridos',
        'name' => 'nombres',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'Los apellidos son requeridos',
        'name' => 'apellidos',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La cédula es requerida',
        'name' => 'cedula',
        'placeholder' => 'Cédula',
        'tipo' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'El usuario es requerido',
        'name' => 'usuario',
        'placeholder' => 'Usuario'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La contraseña es requerida',
        'name' => 'clave',
        'placeholder' => 'Contraseña',
        'tipo' => 'password'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La fecha de nacimiento es requerida',
        'name' => 'fecha_nacimiento',
        'placeholder' => 'Fecha de nacimiento',
        'tipo' => 'date'
      ]
    );

    echo $template::render(
      'componentes/Select',
      [
        'textoDeValidacion' => 'El género es requerido',
        'name' => 'genero',
        'placeholder' => 'Género',
        'opciones' => array_map(fn (Genero $genero): array => [
          'valor' => $genero->value,
          'texto' => $genero->value
        ], Genero::cases())
      ]
    );

    echo $template::render(
      'componentes/Textarea',
      [
        'textoDeValidacion' => 'La dirección es requerida',
        'name' => 'direccion',
        'placeholder' => 'Dirección'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'El teléfono es requerido',
        'tipo' => 'tel',
        'name' => 'telefono',
        'placeholder' => 'Teléfono'
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'El correo es requerido',
        'tipo' => 'email',
        'name' => 'correo',
        'placeholder' => 'Correo'
      ]
    );

    ?>
    <button class="button">Registrarse</button>
  </div>
  <div class="form__background" style="background-image: url(assets/images/OIG1.jpeg)"></div>
</form>
