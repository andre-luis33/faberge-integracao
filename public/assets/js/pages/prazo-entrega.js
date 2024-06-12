import { alerty, allFieldsHaveValue, loader } from "../helper.js"
import DeliveryTimeService from "../services/DeliveryTimeService.js"
import IntegrationSettingsService from "../services/DeliveryTimeService.js"

jQuery(function() {

   const form = $('#delivery-times-form')
   const btnSubmit = $('#btn-submit')

   form.on('submit', async e => {

      e.preventDefault()

      const inputs = form.find('input')
      const payload = []
      let allOk = true

      inputs.each(function() {
         const uf = String($(this).prop('name')).toUpperCase()
         const days = $(this).val() ? parseInt($(this).val()) : null

         if(isNaN(days) || (days && days < 0)) {
            allOk = false
            alerty.show('danger', 'Ah não!', `A uf "${uf}" está com um valor inválido!`)
            return
         }

         payload.push({
            uf, days
         })
      })

      if(!allOk)
         return

      try {

         await DeliveryTimeService.update(payload, btnSubmit)
         alerty.show('success', 'Sucesso!', 'Prazos de entrega atualizados')

      } catch {

      }

   })

   async function renderPage() {

      try {

         loader.show()
         const deliveryTimes = await DeliveryTimeService.get()

         deliveryTimes.forEach(deliveryTime => {
            const { uf, days } = deliveryTime
            const inputSelector = `#input-${uf.toLowerCase()}`

            $(inputSelector).val(days)
         })

      } catch {

      } finally {
         loader.hide()
      }

   }

   renderPage()

})
