<?php

use flight\template\View;
use SARCO\Modelos\Usuario;

/**
 * @var Usuario $usuario
 * @var View $vistas
 */

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Restablecer contraseña
  </h1>
</header>

<form method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'La contraseña debe tener al menos 1 mayúscula,
    1 número y un símbolo',
    'name' => 'nueva_clave',
    'placeholder' => 'Nueva contraseña',
    'type' => 'password',
    'pattern' => '(?=.*\d)(?=.*[A-ZÑ])(?=.*\W).{8,}',
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

  echo '<div class="row">';

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar',
    'onclick' => 'this.form.checkValidity() && this.form.submit()',
    'class' => 'col-md-7 mt-3 mr-md-2'
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'reset',
    'contenido' => 'Empezar de nuevo',
    'class' => 'col-md bg-secondary mt-3 ml-md-2',
    'onclick' => "resetInputs(this.closest('form'))"
  ]);

  echo '</div>';

  ?>
</form>
