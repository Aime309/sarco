const theme = 'semanticui'

export function mostrarPosiblesAlertas() {
  if (messages.error) {
    new Noty({
      text: `<span style="margin-right: 1em">❌</span> ${messages.error}`,
      type: 'error',
      theme,
      timeout: 3000
    }).show()
  } else if (messages.success) {
    new Noty({
      text: `<span style="margin-right: 1em">✅</span> ${messages.success}`,
      type: 'success',
      theme,
      timeout: 3000
    }).show()
  }
}
