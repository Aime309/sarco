<?php

/**
 * @var 'button'|'submit' $tipo
 * @var string $contenido
 */

$class ??= '';

?>

<button type="<?= $tipo ?? 'button' ?>" class="button <?= $class ?>">
  <?= $contenido ?>
</button>
