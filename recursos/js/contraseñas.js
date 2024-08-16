for (const checkbox of document.querySelectorAll('.mostrar-clave input')) {
  checkbox.addEventListener('change', () => {
    const input = checkbox.parentElement.previousElementSibling.firstElementChild

    if (checkbox.checked) {
      input.type = 'text'
    } else {
      input.type = 'password'
    }
  })
}
