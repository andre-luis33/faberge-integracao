import { alerty, allFieldsHaveValue, dateHelper, loader, oneRowTableMessage, skeletonTable } from "../helper.js"
import IntegrationSettingsService from "../services/IntegrationSettingsService.js"

jQuery(function() {

   let LINX_PASSWORD_API_VALUE
   let CILIA_TOKEN_API_VALUE
   let LINX_AUTH_KEY_API_VALUE
   let LINX_STOCK_KEY_API_VALUE

   const statusBtn = $('#status-btn')
   const intervalBtns = $('button[data-interval]')
   const linxUser = $('#linx-user')
   const linxPassword = $('#linx-password')
   const linxAuthKey  = $('#linx-auth-key')
   const linxStockKey = $('#linx-stock-key')
   const ciliaToken = $('#cilia-token')

   const submitBtn = $('#btn-submit')

   const executionsTable = $('#executions-table')
   const executionsCount = $('#executions-count')
   const btnExecutions = $('#btn-executions')
   const btnExecuteIntegration = $('#btn-execute-integration')

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

   btnExecutions.on('click', getLogs)
   btnExecuteIntegration.on('click', executeIntegration)

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
      const authKey  = linxAuthKey.val() === LINX_AUTH_KEY_API_VALUE ? null : linxAuthKey.val()
      const stockKey = linxStockKey.val() === LINX_STOCK_KEY_API_VALUE ? null : linxStockKey.val()

      const payload = {
         interval,
         enabled,
         linx_user: linxUser.val(),
         linx_password: password,
         linx_auth_key: authKey,
         linx_stock_key: stockKey,
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
         const { enabled, interval, linx_password, linx_user, cilia_token, linx_auth_key, linx_stock_key } = settings

         resetIntervalBtns()

         const intervalBtn = $(`button[data-interval="${interval}"]`)
         checkIntervalBtn(intervalBtn)

         statusBtn.prop('checked', enabled)
         linxUser.val(linx_user)
         linxPassword.val(linx_password)
         linxAuthKey.val(linx_auth_key)
         linxStockKey.val(linx_stock_key)
         ciliaToken.val(cilia_token)

         LINX_PASSWORD_API_VALUE = linx_password
         CILIA_TOKEN_API_VALUE = cilia_token
         LINX_AUTH_KEY_API_VALUE = linx_auth_key
         LINX_STOCK_KEY_API_VALUE = linx_stock_key

      } catch {

      } finally {
         loader.hide()
      }

   }

   async function getLogs() {

      try {

         skeletonTable(executionsTable, 5)
         const logs = await IntegrationSettingsService.getLogs()
         listLogs(logs)

      } catch (error) {
         console.error(error)
      }

   }

   async function executeIntegration() {

      try {

         await IntegrationSettingsService.createExectuion(btnExecuteIntegration)
         alerty.show('success', 'Ebaaaaa!', 'Integração realizada com sucesso')

      } catch (error) {
         console.error(error)
      } finally {
         getLogs()
      }

   }

   /**
    * @param {import('../services/IntegrationSettingsService.js').IntegrationExecution[]} logs
    */
   function listLogs(logs) {

      executionsCount.html(logs.length)

      if(logs.length < 1) {
         oneRowTableMessage(executionsTable, 'Nenhuma execução encontrada!')
         return
      }

      const tbody = executionsTable.find('tbody')
      tbody.empty()


      logs.forEach(log => {

         const { cilia_status_code, created_at, error, forced_execution, linx_status_code, has_cilia_payload, id, duration } = log

         let linxStatus = '--', ciliaStatus = '--', csvBtn = '--'

         if(linx_status_code === 200) {
            linxStatus = `<span class="bg-success text-white py-1 px-2 rounded">Sucesso</span>`
         } else if (linx_status_code != null) {
            linxStatus = `<span class="bg-danger text-white py-1 px-2 rounded">Erro</span>`
         }

         if(cilia_status_code === 200) {
            ciliaStatus = `<span class="bg-success text-white py-1 px-2 rounded">Sucesso</span>`
         } else if (cilia_status_code) {
            ciliaStatus = `<span class="bg-danger text-white py-1 px-2 rounded">Erro</span>`
         }


         const seconds = duration.split(':').pop()
         const durationLabel = seconds == '00' ? 'Menos de 1s' : `${seconds}s`

         if(has_cilia_payload) {
            csvBtn = `
               <a href="/admin/integrations/executions/${id}/csv" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="left" title="Baixar CSV enviado para Cilia">
                  <i class="fas fa-file-csv"></i>
               </a>
            `
         }

         tbody.append(`
            <tr>
               <td>${dateHelper.formatToBr(created_at)}</td>
               <td>${durationLabel}</td>
               <td>${forced_execution ? 'Manual' : 'Automático'}</td>
               <td>${linxStatus}</td>
               <td>${ciliaStatus}</td>
               <td class="w-50">${error ?? '--'}</td>
               <td>${csvBtn}</td>
            </tr>
         `)

      })

      $('[data-toggle="tooltip"]').tooltip()
   }

   renderPage()
})
