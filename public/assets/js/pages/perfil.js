import { alerty, allFieldsHaveValue, loader } from "../helper.js"
import ProfileService from "../services/ProfileService.js"

jQuery(function() {


   const email = $('#email')
   const password = $('#password')

   const form = $('#profile-form')
   const btnSubmit = $('#btn-submit')

   form.on('submit', async e => {
      e.preventDefault()

      const isOk = allFieldsHaveValue('[data-required]', true)
      if(!isOk)
         return

      const payload = {
         email: email.val(),
         password: password.val()
      }

      try {

         await ProfileService.update(payload, btnSubmit)
         alerty.show('success', 'Sucesso!', 'Perfil atualizado! Na próxima vez que você realizar o login, deve informar esses dados', 5)

      } catch {

      }

   })


   async function renderPage() {

      loader.show()

      try {

         const profile = await ProfileService.get()
         email.val(profile.email)
         password.val('')

      } catch (error) {
         console.error(error);
      } finally {
         loader.hide()
      }

   }

   renderPage()

})
