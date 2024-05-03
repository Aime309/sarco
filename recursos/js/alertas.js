const theme = 'semanticui'

if (mensajes.error) {
  new Noty({
    text: `<span style="margin-right: 1em">❌</span> ${mensajes.error}`,
    type: 'error',
    theme,
    timeout: 3000
  }).show()
} else if (mensajes.exito) {
  new Noty({
    text: `<span style="margin-right: 1em">✅</span> ${mensajes.exito}`,
    type: 'success',
    theme,
    timeout: 3000
  }).show()
}
