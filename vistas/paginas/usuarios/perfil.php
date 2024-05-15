<?php

use flight\template\View;
use SARCO\Enumeraciones\Genero;
use SARCO\Modelos\Usuario;

assert($vistas instanceof View);
assert($usuario instanceof Usuario);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Editar perfil
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'Los nombres sólo pueden contener letras',
    'name' => 'nombres',
    'placeholder' => 'Nombres',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
    'value' => $usuario->nombres
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'Los apellidos sólo pueden contener letras',
    'name' => 'apellidos',
    'placeholder' => 'Apellidos',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
    'value' => $usuario->apellidos
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La cédula es requerida',
    'name' => 'cedula',
    'placeholder' => 'Cédula',
    'type' => 'number',
    'min' => 1000000,
    'max' => 99999999,
    'value' => $usuario->cedula
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La fecha de nacimiento es requerida',
    'name' => 'fecha_nacimiento',
    'placeholder' => 'Fecha de nacimiento',
    'type' => 'date',
    'value' => $usuario->fechaNacimiento
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El género es requerido',
    'name' => 'genero',
    'placeholder' => 'Género',
    'opciones' => array_map(static fn (Genero $genero): array => [
      'value' => $genero->name,
      'children' => $genero->name,
      'selected' => $usuario->genero()?->esIgualA($genero)
    ], Genero::cases())
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'La dirección es requerida',
    'name' => 'direccion',
    'placeholder' => 'Dirección',
    'minlength' => 3,
    'value' => $usuario->direccion
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
    'type' => 'tel',
    'name' => 'telefono',
    'placeholder' => 'Teléfono',
    'minlength' => 15,
    'maxlength' => 15,
    'pattern' => '\+\d{2} \d{3}-\d{7}',
    'value' => $usuario->telefono
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El correo es requerido',
    'type' => 'email',
    'name' => 'correo',
    'placeholder' => 'Correo',
    'minlength' => 5,
    'value' => $usuario->correo
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  ?>
</form>

<section class="full-box page-header">
  <h2 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Actualizar contraseña
  </h2>
  <p class="text-justify"></p>
</section>

<form method="post" action="./perfil/actualizar-clave" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'La antigua contraseña es requerida',
    'name' => 'antigua_clave',
    'placeholder' => 'Antigua contraseña',
    'type' => 'password',
    'pattern' => '(.+){8,}',
    'minlength' => 8
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'La nueva contraseña es requerida',
    'name' => 'nueva_clave',
    'placeholder' => 'Nueva contraseña',
    'type' => 'password',
    'pattern' => '(.+){8,}',
    'minlength' => 8
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'No coincide con la nueva contraseña',
    'name' => 'confirmar_clave',
    'placeholder' => 'Confirmar contraseña',
    'type' => 'password',
    'pattern' => '(.+){8,}',
    'minlength' => 8
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  ?>
</form>

<form action="./usuarios/<?= $usuario->cedula ?>/desactivar" class="my-5 form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <div style="text-align: end">
    <?php

    $vistas->render('componentes/Boton', [
      'tipo' => 'submit',
      'contenido' => 'Desactivar cuenta',
      'class' => 'bg-danger w-50'
    ]);

    ?>
  </div>
</form>
