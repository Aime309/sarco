<?php

use flight\template\View;

/** @var View $vistas */

?>

<div class="modal fade" id="buscar-maestro">
  <div class="modal-dialog">
    <form action="./maestros" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar maestro</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php

        $vistas->render('componentes/Input', [
          'validacion' => 'La cédula es requerida',
          'name' => 'cedula',
          'placeholder' => 'Cédula',
          'type' => 'number',
          'min' => 1000000,
          'max' => 99999999
        ]);

        ?>

        <div class="text-center">
          <div class="spinner-border d-none"></div>
        </div>

        <ul id="lista-maestros" class="list-group"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cancelar
        </button>
        <button class="btn btn-primary">Buscar</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const $cedula = document.querySelector('#buscar-maestro [name="cedula"]')
    const $spinner = document.querySelector('#buscar-maestro .spinner-border')
    const $lista = document.querySelector('#lista-maestros')

    let interval

    $cedula.addEventListener('keydown', () => {
      clearInterval(interval)

      interval = setTimeout(async () => {
        $spinner.classList.remove('d-none')
        let maestros = await buscarMaestros($cedula.value)
        $lista.innerHTML = maestros.map(maestro => `
          <li class="list-group-item p-2">
            <a href="./maestros/${maestro.cedula}">
              ${maestro.cedula} ~ ${maestro.nombres} ${maestro.apellidos}
            </a>
          </li>
        `).join('')
        $spinner.classList.add('d-none')
      }, 0)
    })
  })

  async function buscarMaestros(cedula) {
    return new Promise((resolve) => {
      setTimeout(() => {
        fetch('./api/maestros?cedula=' + cedula)
          .then(respuesta => respuesta.json())
          .then(resolve)
      }, 0)
    })
  }
</script>
