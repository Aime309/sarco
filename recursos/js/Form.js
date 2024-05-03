export default class Form {
  constructor() {
    /**
     * @private
     * @type {NodeListOf<HTMLInputElement>}
     */
    this.inputs = document.querySelectorAll('input-group__input')

    /**
     * @private
     * @type {NodeListOf<HTMLInputElement>}
     */
    this.inputsToValidate = document.querySelectorAll(
      '.input-group--with-validation .input-group__input'
    )

    /**
     * @private
     * @type {HTMLFormElement}
     */
    this.form = document.querySelector('.form--with-validation')

    this.addEvents()
  }

  /** @private */
  addEvents() {
    for (const input of this.inputs) {
      input.addEventListener('blur', () => {
        if (input.value.trim() !== '') {
          return input.classList.add('input-group__input--is-validated')
        }

        input.classList.remove('input-group__input--is-validated')
      })
    }

    for (const input of this.inputsToValidate) {
      input.addEventListener('focus', () => this.hideValidate(input))
    }

    this.form.addEventListener('submit', event => {
      let check = true

      for (const input of this.inputsToValidate) {
        if (this.validate(input) === false) {
          this.showValidate(input)
          check = false
        }
      }

      if (!check) {
        event.preventDefault()
      }
    })
  }

  /**
   * @private
   * @param  {HTMLInputElement} input
   */
  validate(input) {
    const patterns = Object.freeze({
      email:
        /^([\w\-\.]+)@((\[[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[\d]{1,3})(\]?)$/,
      number: /^\d+$/
    })

    if (input.type === 'email') {
      if (input.value.trim().match(patterns[input.type]) == null) {
        return false
      }
    }

    if (input.type === 'number') {
      if (input.value.trim().match(patterns[input.type]) == null) {
        return false
      }
    }

    if (input.value.trim() === '') {
      return false
    }

    return true
  }

  /**
   * @private
   * @param  {HTMLInputElement} input
   */
  showValidate(input) {
    input.parentElement.classList.add('input-group__input--show-error')
  }

  /**
   * @private
   * @param  {HTMLInputElement} input
   */
  hideValidate(input) {
    input.parentElement.classList.remove('input-group__input--show-error')
  }
}
