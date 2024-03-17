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
  <link rel="stylesheet" href="assets/css/reset.css" />
  <link rel="stylesheet" href="assets/css/button.css" />
  <link rel="stylesheet" href="assets/css/form.css" />
  <link rel="stylesheet" href="assets/css/responsive.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/noty.css" />
  <link rel="stylesheet" href="node_modules/noty/lib/themes/semanticui.css" />
  <script>
    window.messages = JSON.parse('<?= json_encode(compact('error', 'success')) ?>')
  </script>
</head>

<body>
  <?= $pagina ?>
  <script src="node_modules/noty/lib/noty.min.js"></script>
  <?php foreach ($scripts() as $script): ?>
    <script type="module" src="<?= $script ?>"></script>
  <?php endforeach ?>
</body>

</html>
