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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= $root ?>" />
  <link rel="icon" href="recursos/iconos/favicon.ico" />
  <link rel="stylesheet" href="recursos/css/reinicio.css" />
  <link rel="stylesheet" href="recursos/css/botones.css" />
  <link rel="stylesheet" href="recursos/css/formularios.css" />
  <link rel="stylesheet" href="recursos/css/responsive.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/noty.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/themes/semanticui.css" />
</head>

<body>
  <?= $pagina ?>
  <script>
    window.mensajes = JSON.parse('<?= json_encode($mensajes) ?>')
  </script>
  <script src="node_modules/noty/lib/noty.min.js"></script>
  <?php foreach (scripts() as $ruta) : ?>
    <script type="module" src="<?= $ruta ?>"></script>
  <?php endforeach ?>
</body>

</html>
