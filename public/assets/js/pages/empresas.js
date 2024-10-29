import { alerty, allFieldsHaveValue, dateHelper, loader, masks, skeletonTable } from "../helper.js"
import CompanyService from "../services/CompanyService.js"

jQuery(function() {

   /** @type {import('../services/CompanyService.js').Company[]} */
   let COMPANIES_GLOBAL

   const companiesTable = $('#companies-table')
   const companiesCount = $('#companies-count')
   const companiesModal = $('#companies-modal')

   const form = {
      form: $('#companies-form'),
      id: $('#input-id'),
      cnpj: $('#input-cnpj'),
      name: $('#input-name'),
      primaryTrue: $('#input-true'),
      primaryFalse: $('#input-false'),
      btnSubmit: $('#btn-submit'),
   }

   companiesModal.on('show.bs.modal', e => {
      form.form.trigger('reset')
   })

   form.form.on('submit', async e => {
      e.preventDefault()

      const formOk = allFieldsHaveValue('[data-required]')
      if(!formOk)
         return

      const payload = {
         cnpj: masks.cnpj.remove(form.cnpj.val()),
         name: form.name.val(),
         primary: form.primaryTrue.is(':checked'),
      }

      const companyId = form.id.val()
      const isUpdate = companyId != ''

      submitForm(isUpdate, payload)

      try {
         if(isUpdate)
            await CompanyService.update(companyId, payload, form.btnSubmit)
         else
            await CompanyService.create(payload, form.btnSubmit)

         alerty.show('success', 'Sucesso!', 'Operação realizada com sucesso')
         companiesModal.modal('hide')

         renderPage()

      } catch (error) {
         console.log(error);
      }

   })

   function submitForm(isUpdate, payload) {

   }

   /**
    * @param companies
    */
   function listCompanies() {

      const tbody = companiesTable.find('tbody')
      tbody.empty()
      companiesCount.html(`(${COMPANIES_GLOBAL.length})`)

      COMPANIES_GLOBAL.forEach(company => {

         const { id, name, cnpj, created_at, active, primary, last_execution_successful, last_execution_at } = company

         const primaryLabel = !primary ? '' : `
            <span class="bg-purple-primary text-light py-1 badge rounded">
               <i class="fas fa-crown" data-toggle="tooltip" data-placement="right" title="Essa é a empresa que será selecionada no momento do login"></i>
            </span>
         `

         const updateBtn = !active ? '--' : `
            <button class="btn btn-sm btn-purple" data-id="${id}">
               <i class="fas fa-edit"></i>
            </button>
         `

         const style = active ? "" : 'style="opacity: 0.3; cursor: not-allowed'

         tbody.append(`<tr ${style}">
            <td>${primaryLabel} ${active ? '' : '[DESATIVADA]'} ${name}</td>
            <td>${masks.cnpj.apply(cnpj)}</td>
            <td data-last-execution data-id="${id}"><i class="fas fa-spin fa-circle-notch"></i></td>
            <td>${dateHelper.formatToBr(created_at)}</td>
            <td>${updateBtn}</td>
         </tr>`)
      })

      $('[data-toggle="tooltip"]').tooltip()

      $('[data-id]').on('click', function() {
         const id = parseInt($(this).attr('data-id'))
         callEditModal(id)
      })

   }

   function callEditModal(id) {
      const company = COMPANIES_GLOBAL.find(company => company.id === id)
      if(!company) {
         alerty.show('danger', 'Erro', 'Erro ao buscar os dados para edição')
         return
      }

      companiesModal.modal('show')

      form.id.val(company.id)
      form.cnpj.val(masks.cnpj.apply(company.cnpj))
      form.name.val(company.name)

      if(company.primary) {
         form.primaryTrue.prop('checked', true)
      } else {
         form.primaryFalse.prop('checked', true)
      }

   }

   async function getLastestExecutions() {

      try {

         const executions = await CompanyService.getLastExecutions()
         let delay = 0
         const allDelays = (executions.length * 50) + 20

         $('[data-last-execution]').each(function() {
            const id = $(this).attr('data-id')

            const execution = executions.find(execution => execution.company_id == id)

            const lastExecutionLabel = !execution ? '<i>Nenhuma integração encontrada</i>' : `
               <span class="bg-${execution.last_execution_successful ? 'success' : 'danger'} text-light p-1 rounded" data-toggle="tooltip" data-placement="right" title="Última integração foi nessa data e ${execution.last_execution_successful ? 'deu tudo certo' : 'aconteceu um erro'}">
                  <i class="fas fa-${execution.last_execution_successful ? 'check' : 'times'}-circle"></i>
                  ${dateHelper.formatToBr(execution.last_execution_at)}
               </span>
            `
            setTimeout(() => {
            $(this).html(lastExecutionLabel)
            }, delay)

            delay+=50
         })

         setTimeout(() => {
            $('[data-toggle="tooltip"]').tooltip()
         }, allDelays)


      } catch (err) {
         console.log(err);
      }

   }

   async function renderPage() {

      loader.show()

      try {

         const companies = await CompanyService.get()
         COMPANIES_GLOBAL = companies

         listCompanies()

         getLastestExecutions()

      } catch (error) {
         console.error(error);
      } finally {
         loader.hide()
      }

   }

   renderPage()

})
