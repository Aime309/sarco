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
      location.href = './salir'
    }
  })
})
