<?php

use Leaf\BareUI;
use SARCO\Modelos\Usuario;

/**
 * @var string $titulo
 * @var callable(?string $script): string[] $scripts
 * @var string $pagina
 * @var BareUI $template
 */

/** @var Usuario $usuario */
global $usuario;

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>SARCO | <?= $titulo ?></title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="assets/images/icons/favicon.ico" />
  <link rel="stylesheet" href="node_modules/normalize.css/normalize.css" />
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/libs/bootstrap/bootstrap-material-design.min.css" />
  <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css" />
  <link rel="stylesheet" href="assets/libs/jquery/jquery.mCustomScrollbar.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <script>
    window.messages = JSON.parse('<?= json_encode(compact('error', 'success')) ?>')
  </script>
</head>

<body>
  <main class="full-box main-container">
    <?= $template::render('componentes/MenuLateral', compact('usuario')) ?>
    <section class="full-box page-content">
      <?= $template::render('componentes/BarraSuperior') ?>
      <?= $pagina ?>
    </section>
  </main>
  <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
  <script src="assets/libs/jquery/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="assets/libs/popper/popper.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="assets/libs/bootstrap/bootstrap-material-design.min.js"></script>
  <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
  <script src="assets/js/cerrar-sesion.js"></script>
  <script>
    $(document).ready(function() {
      $('body').bootstrapMaterialDesign();
    });
  </script>
  <script src="assets/js/alertas.js"></script>
  <script src="assets/js/main.js"></script>
  <?php foreach ($scripts() as $script) : ?>
    <script type="module" src="<?= $script ?>"></script>
  <?php endforeach ?>
</body>

</html>
