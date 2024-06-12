import { alerty, allFieldsHaveValue, loader } from "../helper.js"
import IntegrationSettingsService from "../services/IntegrationSettingsService.js"

jQuery(function() {

   let LINX_PASSWORD_API_VALUE
   let CILIA_TOKEN_API_VALUE

   const statusBtn = $('#status-btn')
   const intervalBtns = $('button[data-interval]')
   const linxUser = $('#linx-user')
   const linxPassword = $('#linx-password')
   const ciliaToken = $('#cilia-token')

   const submitBtn = $('#btn-submit')

   const allowedIntervals = [15, 30, 45, 60]

   intervalBtns.on('click', function() {
      resetIntervalBtns()
      checkIntervalBtn($(this))
   })

   submitBtn.on('click', submit)
   $(window).on('keyup', e => {
      if(e.key === 'Enter')
         submit()
   })


   function resetIntervalBtns() {
      intervalBtns.removeAttr('data-checked').removeClass().addClass('btn btn-outline-purple')
   }

   function checkIntervalBtn(btn) {
      btn.removeClass('btn-outline-purple').addClass('btn-purple').attr('data-checked', true)
   }

   async function submit() {

      const enabled = statusBtn.is(':checked')
      const interval = parseInt(intervalBtns.filter("[data-checked]").attr('data-interval'))
      if(!allowedIntervals.includes(interval)) {
         alerty.show('danger', 'Ainda não!', 'Por favor, selecione um intervalo válido')
         return
      }

      const requiredFieldsOk = allFieldsHaveValue('[data-required]')
      if(!requiredFieldsOk) {
         alerty.show('danger', 'Ainda não!', 'Por favor, preencha todos os campos!')
         return
      }

      // prevents sending **** to the api when the user don't change their value
      const password = linxPassword.val() === LINX_PASSWORD_API_VALUE ? null : linxPassword.val()
      const token    = ciliaToken.val() === CILIA_TOKEN_API_VALUE ? null : ciliaToken.val()

      const payload = {
         interval,
         enabled,
         linx_user: linxUser.val(),
         linx_password: password,
         cilia_token: token
      }

      try {

         await IntegrationSettingsService.update(payload, submitBtn)
         alerty.show('success', 'Sucesso!', 'Parâmetros de integração atualizados', 1)
         renderPage()

      } catch {

      }


   }

   async function renderPage() {

      try {
         loader.show()

         const settings = await IntegrationSettingsService.get()
         const { enabled, interval, linx_password, linx_user, cilia_token } = settings

         resetIntervalBtns()

         const intervalBtn = $(`button[data-interval="${interval}"]`)
         checkIntervalBtn(intervalBtn)

         statusBtn.prop('checked', enabled)
         linxUser.val(linx_user)
         linxPassword.val(linx_password)
         ciliaToken.val(cilia_token)

         LINX_PASSWORD_API_VALUE = linx_password
         CILIA_TOKEN_API_VALUE = cilia_token

      } catch {

      } finally {
         loader.hide()
      }

   }



   renderPage()
})
