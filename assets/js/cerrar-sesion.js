const btnSalir = document.querySelector('.btn-exit-system')

btnSalir.addEventListener('click', event => {
  event.preventDefault()

  Swal.fire({
    title: 'Quieres salir del sistema?',
    text: 'La sesion actual se cerrara y saldras del sistema',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, salir',
    cancelButtonText: 'No, cancelar'
  }).then(result => {
    if (result.value) {
      const url = '<?php echo SERVERURL; ?>ajax/loginAjax.php'
      const token = "<?php echo $lc->encryption($_SESSION['token_spm;(']); ?>')"
      const usuario = "<?php echo $lc->encryption($_SESSION['usuario_spm;(']); ?>')"

      const datos = new FormData()
      datos.append('token', token)
      datos.append('usuario', usuario)

      fetch(url, {
        method: 'POST',
        body: datos
      })
        .then(respuesta => respuesta.json())
        .then(respuesta => {
          return alertas_ajax(respuesta)
        })
    }
  })
})
