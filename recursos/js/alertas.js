const theme = 'semanticui'

if (mensajes.error) {
  new Noty({
    text: `<span style="margin-right: 1em">❌</span> ${mensajes.error}`,
    type: 'error',
    theme,
    timeout: 10000
  }).show()
} else if (mensajes.exito) {
  new Noty({
    text: `<span style="margin-right: 1em">✅</span> ${mensajes.exito}`,
    type: 'success',
    theme,
    timeout: 10000
  }).show()
} else if (mensajes.advertencia) {
  new Noty({
    text: `<span style="margin-right: 1em">⚠️</span> ${mensajes.advertencia}`,
    type: 'warning',
    theme,
    timeout: 10000
  }).show()
}
