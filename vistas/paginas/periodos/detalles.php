<?php

use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;

/**
 * @var array{
 *   salas: Sala[]
 * } $detalles
 */

assert($periodo instanceof Periodo);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    Período <?= $periodo ?>
  </h1>
  <div class="text-right">
    <button class="btn btn-danger" onclick="imprimir()">
      <i class="fas fa-print"></i>
      Imprimir
    </button>
  </div>
</div>

<script src="./recursos/libs/chart/ResizeObserver.global.js"></script>
<script src="./recursos/libs/chart/chart.js"></script>

<script>
  function imprimir() {
    if (!document.querySelector('.nav-lateral').classList.contains('active')) {
      document.querySelector('.show-nav-lateral').click()
    }

    print()
  }
</script>
