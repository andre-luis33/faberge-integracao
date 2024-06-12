import { allFieldsHaveValue } from "./helper.js"
import AuthService from "./services/AuthService.js"

jQuery(function() {

   const form = $('form')
   const btnSubmit = form.find('#btn-submit')

   const inputEmail = $("#email")
   const inputPassword = $("#password")

   form.on("submit", async e => {
      e.preventDefault()

      const formOk = allFieldsHaveValue('[data-required]')
      if(!formOk)
         return

      const email = inputEmail.val()
      const password = inputPassword.val()

      try {

         await AuthService.login({ email, password }, btnSubmit)
         window.location.href = '/admin/inicio'

      } catch (error) {

         form.find('.alert').remove()
         form.prepend(`<div class="alert alert-danger text-center">Erro: ${error.responseJSON.message}</div>`)

      }

   })

})
