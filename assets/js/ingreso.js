import Form from './Form.js'
import { theme } from './globales.js'

new Form()

if (messages.error) {
  new Noty({
    text: messages.error,
    type: 'error',
    theme,
    timeout: 3000
  }).show()
} else if (messages.success) {
  new Noty({
    text: messages.success,
    type: 'success',
    theme
  }).show()
}
