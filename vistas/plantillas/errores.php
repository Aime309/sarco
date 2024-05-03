<?php

assert(is_string($titulo));
assert(is_string($pagina));
assert(is_string($root));

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
  <link rel="stylesheet" href="recursos/css/botones.css" />
  <link rel="stylesheet" href="recursos/css/textos.css" />
  <link rel="stylesheet" href="recursos/css/tema.css" />
</head>

<body><?= $pagina ?></body>

</html>
