<?php

/**
 * @var 'button'|'submit'|'reset' $tipo
 * @var string $contenido
 */

$class ??= '';
$onclick ??= '';
$tipo ??= 'button';

?>

<button
  onclick="<?= $onclick ?>"
  type="<?= $tipo ?>"
  class="button <?= $class ?>">
  <?= $contenido ?>
</button>
