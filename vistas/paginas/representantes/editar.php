<?php

use flight\template\View;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Nacionalidad;
use SARCO\Modelos\Representante;

assert($vistas instanceof View);
assert($representante instanceof Representante);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Editar representante
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./representantes/<?= $representante->cedula ?>" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'Los nombres sólo pueden contener letras',
    'name' => 'nombres',
    'placeholder' => 'Nombres',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
    'value' => $representante->nombres
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'Los apellidos sólo pueden contener letras',
    'name' => 'apellidos',
    'placeholder' => 'Apellidos',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
    'value' => $representante->apellidos
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La cédula es requerida',
    'name' => 'cedula',
    'placeholder' => 'Cédula',
    'type' => 'number',
    'min' => 1000000,
    'max' => 99999999,
    'value' => $representante->cedula
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La fecha de nacimiento es requerida',
    'name' => 'fecha_nacimiento',
    'placeholder' => 'Fecha de nacimiento',
    'type' => 'date',
    'value' => $representante->fechaNacimiento
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El género es requerido',
    'name' => 'genero',
    'placeholder' => 'Género',
    'opciones' => array_map(static fn (Genero $genero): array => [
      'value' => $genero->name,
      'children' => $genero->name,
      'selected' => $representante->genero()->esIgualA($genero)
    ], Genero::cases())
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El estado civil es requerido',
    'name' => 'estado_civil',
    'placeholder' => 'Estado civil',
    'opciones' => array_map(static fn (EstadoCivil $estado): array => [
      'value' => $estado->name,
      'children' => $estado->name,
      'selected' => $representante->estadoCivil()->esIgualA($estado)
    ], EstadoCivil::cases())
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'La nacionalidad es requerido',
    'name' => 'nacionalidad',
    'placeholder' => 'Nacionalidad',
    'opciones' => array_map(static fn (Nacionalidad $nacionalidad): array => [
      'value' => $nacionalidad->name,
      'children' => $nacionalidad->name,
      'selected' => $representante->nacionalidad()->esIgualA($nacionalidad)
    ], Nacionalidad::cases())
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
    'type' => 'tel',
    'name' => 'telefono',
    'placeholder' => 'Teléfono',
    'minlength' => 15,
    'maxlength' => 15,
    'pattern' => '\+\d{2} \d{3}-\d{7}',
    'value' => $representante->telefono
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El correo es requerido',
    'type' => 'email',
    'name' => 'correo',
    'placeholder' => 'Correo',
    'minlength' => 5,
    'value' => $representante->correo
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar',
    'onclick' => 'this.form.checkValidity() && this.form.submit()'
  ]);

  ?>
</form>
