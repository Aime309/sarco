<?php

use flight\template\View;

/** @var View $vistas */

?>

<div class="modal fade" id="buscar-estudiante">
  <div class="modal-dialog">
    <form action="./estudiantes" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar estudiante</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php

        $vistas->render('componentes/Input', [
          'validacion' => 'La cédula escolar es requerida (v-1__________)',
          'name' => 'cedula',
          'placeholder' => 'Cédula escolar',
          'pattern' => 'v-1\d{2}\d{7,8}',
          'value' => 'v-1'
        ]);

        ?>

        <div class="text-center">
          <div class="spinner-border d-none"></div>
        </div>

        <ul id="lista-estudiantes" class="list-group"></ul>
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
    const $cedula = document.querySelector('#buscar-estudiante [name="cedula"]')
    const $spinner = document.querySelector('#buscar-estudiante .spinner-border')
    const $lista = document.querySelector('#lista-estudiantes')

    let interval

    $cedula.addEventListener('keydown', () => {
      clearInterval(interval)

      interval = setTimeout(async () => {
        $spinner.classList.remove('d-none')
        let estudiantes = await buscarEstudiantes($cedula.value)
        $lista.innerHTML = estudiantes.map(estudiante => `
          <li class="list-group-item p-2">
            <a href="./estudiantes?cedula=${estudiante.cedula}">
              ${estudiante.cedula} ~ ${estudiante.nombres} ${estudiante.apellidos}
            </a>
          </li>
        `).join('')
        $spinner.classList.add('d-none')
      }, 0)
    })
  })

  async function buscarEstudiantes(cedula) {
    return new Promise((resolve) => {
      setTimeout(() => {
        fetch('./api/estudiantes?cedula=' + cedula)
          .then(respuesta => respuesta.json())
          .then(estudiantes => {
            resolve(estudiantes)
          })
      }, 0)
    })
  }
</script>
