<?php

use flight\template\View;
use SARCO\Modelos\Usuario;

assert(is_string($titulo));
assert(is_string($pagina));
assert(is_string($root));
assert($vistas instanceof View);
assert($usuario instanceof Usuario);

$mensajes = [
  'error' => @$_SESSION['mensajes.error'],
  'exito' => @$_SESSION['mensajes.exito'],
  'advertencia' => @$_SESSION['mensajes.advertencia'],
];

unset($_SESSION['mensajes.error']);
unset($_SESSION['mensajes.exito']);
unset($_SESSION['mensajes.advertencia']);

scripts('recursos/js/alertas.js');
scripts('recursos/js/cerrar-sesion.js');

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>SARCO | <?= $titulo ?></title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= $root ?>" />
  <link rel="icon" href="recursos/iconos/favicon.ico" />
  <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/noty.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/themes/semanticui.css" />
  <link rel="stylesheet" href="recursos/libs/bootstrap/bootstrap-material-design.min.css" />
  <link rel="stylesheet" href="recursos/libs/jquery/jquery.mCustomScrollbar.css" />
  <link rel="stylesheet" href="recursos/css/reinicio.css" />
  <link rel="stylesheet" href="recursos/css/tema.css" />
  <link rel="stylesheet" href="recursos/css/formularios.css" />
  <link rel="stylesheet" href="recursos/css/botones.css" />
  <style>
    th,
    td {
      vertical-align: middle !important;
      white-space: nowrap;
    }

    .nav-pills .nav-item.show .nav-link,
    .nav-pills .nav-link.active,
    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
      background-color: #f44336;
      color: white;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <main class="full-box main-container">
    <?= $vistas->fetch('componentes/MenuLateral', compact('usuario')) ?>
    <section class="full-box page-content">
      <?= $vistas->fetch('componentes/BarraSuperior') ?>
      <?= $pagina ?>
    </section>
  </main>
  <script>
    window.mensajes = JSON.parse('<?= json_encode($mensajes) ?>')
  </script>
  <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
  <script src="recursos/libs/jquery/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="node_modules/noty/lib/noty.min.js"></script>
  <script type="module" src="recursos/js/main.js"></script>
  <?php foreach (scripts() as $ruta) : ?>
    <script type="module" src="<?= $ruta ?>"></script>
  <?php endforeach ?>
</body>

</html>
