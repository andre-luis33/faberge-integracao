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
         active: true,
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

         const { id, name, cnpj, created_at, active, primary } = company

         const primaryLabel = !primary ? '' : `
            <span class="bg-purple-primary text-light py-1 badge rounded">
               <i class="fas fa-crown" data-toggle="tooltip" data-placement="top" title="Essa é a empresa que será selecionada no momento do login"></i>
            </span>
         `

         tbody.append(`<tr>
            <td>${primaryLabel} ${name}</td>
            <td>${masks.cnpj.apply(cnpj)}</td>
            <td>${dateHelper.formatToBr(created_at)}</td>
            <td>
               <button class="btn btn-sm btn-purple" data-id="${id}">
                  <i class="fas fa-edit"></i>
               </button>
            </td>
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

   async function renderPage() {

      loader.show()

      try {

         const companies = await CompanyService.get()
         COMPANIES_GLOBAL = companies

         listCompanies()

      } catch (error) {
         console.error(error);
      } finally {
         loader.hide()
      }

   }

   renderPage()

})
