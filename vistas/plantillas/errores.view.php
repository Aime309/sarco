<?php

/**
 * @var string $titulo
 * @var callable(?string $script): string[] $scripts
 * @var string $pagina
 */

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
  <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/button.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <script>
    window.messages = JSON.parse('<?= json_encode(compact('error', 'success')) ?>')
  </script>
</head>

<body>
  <?= $pagina ?>
  <?php foreach ($scripts() as $script) : ?>
    <script type="module" src="<?= $script ?>"></script>
  <?php endforeach ?>
</body>

</html>
