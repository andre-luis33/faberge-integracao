import { alerty, waitForm, masks } from "./helper.js";

export const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content').toString()

export const BASE_URL = '/'
export const API_URL = '/api'

// Sets default values and configs to $.ajax
$.ajaxSetup({
   contentType: 'application/json',
   dataType: 'json',
   showAlert: true,
   csrfToken: true,
   beforeSend: (jqXHR, settings) => {
      if (settings.waitButton && settings.waitButton.length > 0) {
         waitForm(settings.waitButton, true)
      }

      if (settings.csrfToken) {
         jqXHR.setRequestHeader('X-CSRF-Token', CSRF_TOKEN);
         $('html').css('cursor', 'wait')
      }
   },
})

$(document).on("ajaxComplete", function (event, xhr, settings) {
   if (settings.waitButton && settings.waitButton.length > 0) {
      waitForm(settings.waitButton, false)
   }

   $('html').css('cursor', 'auto')

   if (xhr.status > 299 && settings.showAlert) {

      const status = xhr.status
      let errorMessage = ''


      // Laravel's response error to failed form request validation
      if (xhr.status === 422) {
         const { message } = xhr.responseJSON;
         let messageList = ''

         if (typeof message === 'object') {
            const keys = Object.keys(message)
            keys.forEach(key => {
               messageList += `<li>- ${message[key]}</li>`
            })
         } else {
            messageList += `<li>- ${message}</li>`
         }


         const body = `
            Alguns campos enviados são inválidos:
            <ul>${messageList}</ul>
         `

         alerty.show('danger', 'Erro 422', body, 5)
         return
      }


      if (xhr.responseJSON && typeof xhr.responseJSON.message === 'string') {
         errorMessage = xhr.responseJSON.message
      } else {
         errorMessage = xhr.status >= 500 ? 'Erro interno no servidor' : 'Erro ao efetuar operação'
      }


      alerty.show('danger', `Erro ${status}!`, errorMessage, 5)
   }
});

$('[data-mask]').on('keyup input change focusout blur', function () {
   const value = $(this).val()
   const wichMask = $(this).attr('data-mask')

   if (wichMask === 'money') {
      $(this).val(masks.money.apply(value))
   } else if (wichMask === 'zipCode') {
      $(this).val(masks.zipCode.apply(value))
   } else if (wichMask === 'phone') {
      $(this).val(masks.phone.apply(value))
   } else if (wichMask === 'cnpj') {
      $(this).val(masks.cnpj.apply(value))
   } else if (wichMask === 'cpf') {
      $(this).val(masks.cpf.apply(value))
   } else if (wichMask === 'cpfCnpj') {
      $(this).val(masks.cpfCnpj.apply(value))
   }
})

