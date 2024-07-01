<?php

use flight\template\View;

/** @var View $vistas */

?>

<div class="modal fade" id="buscar-representante">
  <div class="modal-dialog">
    <form action="./representantes" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar representante</h5>
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

        <ul id="lista-representantes" class="list-group"></ul>
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
    const $cedula = document.querySelector('#buscar-representante [name="cedula"]')
    const $spinner = document.querySelector('#buscar-representante .spinner-border')
    const $lista = document.querySelector('#lista-representantes')

    let interval

    $cedula.addEventListener('keydown', () => {
      clearInterval(interval)

      interval = setTimeout(async () => {
        $spinner.classList.remove('d-none')
        let representantes = await buscarRepresentante($cedula.value)
        $lista.innerHTML = representantes.map(representante => `
          <li class="list-group-item p-2">
            <a href="./representantes/${representante.cedula}">
              ${representante.cedula} ~ ${representante.nombres} ${representante.apellidos}
            </a>
          </li>
        `).join('')
        $spinner.classList.add('d-none')
      }, 0)
    })
  })

  async function buscarRepresentante(cedula) {
    return new Promise((resolve) => {
      setTimeout(() => {
        fetch('./api/representantes?cedula=' + cedula)
          .then(respuesta => respuesta.json())
          .then(resolve)
      }, 0)
    })
  }
</script>
