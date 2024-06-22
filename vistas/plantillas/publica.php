<?php

assert(is_string($titulo));
assert(is_string($pagina));
assert(is_string($root));

scripts('recursos/js/alertas.js');

$mensajes = [
  'error' => @$_SESSION['mensajes.error'],
  'exito' => @$_SESSION['mensajes.exito']
];

unset($_SESSION['mensajes.error']);
unset($_SESSION['mensajes.exito']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>SARCO | <?= $titulo ?></title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <base href="<?= $root ?>" />
  <link rel="icon" href="recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="recursos/css/reinicio.css" />
  <link rel="stylesheet" href="recursos/css/botones.css" />
  <link rel="stylesheet" href="recursos/css/formularios.css" />
  <link rel="stylesheet" href="recursos/css/responsive.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/noty.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/themes/semanticui.css" />
  <link rel="stylesheet" href="node_modules/pure-css-loader/dist/loader-bouncing.css" />
</head>

<body>
  <div class="loader loader-bouncing"></div>
  <?= $pagina ?>
  <script>
    window.$loader = document.querySelector('.loader')

    window.$loader.show = function () {
      this.classList.add('is-active')
    }

    window.$loader.hide = function () {
      this.classList.remove('is-active')
    }

    window.mensajes = JSON.parse('<?= json_encode($mensajes) ?>')
  </script>
  <script src="node_modules/noty/lib/noty.min.js"></script>
  <?php foreach (scripts() as $ruta) : ?>
    <script type="module" src="<?= $ruta ?>"></script>
  <?php endforeach ?>
</body>

</html>
