import { alerty, allFieldsHaveValue, loader } from "../helper.js"
import IntegrationSettingsService from "../services/IntegrationSettingsService.js"

jQuery(function() {

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


      const payload = {
         interval,
         enabled,
         linx_user: linxUser.val(),
         linx_password: linxPassword.val(),
         cilia_token: ciliaToken.val()
      }

      try {

         await IntegrationSettingsService.update(payload, submitBtn)
         alerty.show('success', 'Sucesso!', 'Parâmetros de integração atualizados', 1)

      } catch {

      }


   }

   async function renderPage() {

      try {
         loader.show()

         const settings = await IntegrationSettingsService.get()
         const { enabled, interval } = settings

         resetIntervalBtns()

         const intervalBtn = $(`button[data-interval="${interval}"]`)
         checkIntervalBtn(intervalBtn)

         statusBtn.prop('checked', enabled)

      } catch {

      } finally {
         loader.hide()
      }

   }



   renderPage()
})
