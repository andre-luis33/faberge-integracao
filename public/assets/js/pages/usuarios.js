import { alerty, allFieldsHaveValue, dateHelper, loader, masks, skeletonTable } from "../helper.js"
import UserService from "../services/UserService.js"

jQuery(function() {

   /** @type {import('../services/UserService.js').User[]} */
   let USERS_GLOBAL

   const usersTable = $('#users-table')
   const usersCount = $('#users-count')
   const usersModal = $('#users-modal')

   const DEFAULT_LOGO_URL = 'https://dealerstrg.blob.core.windows.net/apps/cilia/logo-dealer.png'

   const form = {
      form: $('#users-form'),
      companyCnpj: $('#input-company-cnpj'),
      companyName: $('#input-company-name'),
      name: $('#input-name'),
      email: $('#input-email'),
      logoUrl: $('#input-logo-url'),
      password: $('#input-password'),
      btnSubmit: $('#btn-submit'),
   }

   usersModal.on('show.bs.modal', e => {
      form.form.trigger('reset')
      form.logoUrl.val(DEFAULT_LOGO_URL)
   })

   form.form.on('submit', async e => {
      e.preventDefault()

      const formOk = allFieldsHaveValue('[data-required]', true)
      if(!formOk)
         return

      const payload = {
         name: form.name.val(),
         email: form.email.val(),
         password: form.password.val(),
         logo_url: form.logoUrl.val(),
         company: {
            name: form.companyName.val(),
            cnpj: masks.cnpj.remove(form.companyCnpj.val())
         }
      }

      try {

         await UserService.create(payload, form.btnSubmit)
         alerty.show('success', 'Uhuuuul!', 'Usuário criado com sucesso, anota a senha heinn')
         renderPage()

         usersModal.modal('hide')

      } catch (error) {
         console.log(error);
      }

   })


   /**
    * @param users
    */
   function listUsers() {

      const tbody = usersTable.find('tbody')
      tbody.empty()
      usersCount.html(`(${USERS_GLOBAL.length})`)

      USERS_GLOBAL.forEach(user => {

         const { id, name, email, created_at, companies, active } = user

         const companiesLabel = `<u data-toggle="tooltip" data-placement="left" title="${companies.join(', ')}">${companies.length} empresas</u>`

         tbody.append(`<tr ${!active ? 'style="opacity: .5"' : ''}>
            <td>${name}</td>
            <td>${email}</td>
            <td>${companiesLabel}</td>
            <td>${dateHelper.formatToBr(created_at)}</td>
            <td>
               <label class="switch">
                  <input type="checkbox" data-status-btn data-id="${id}" ${active ? 'checked' : ''}>
                  <span class="slider round"></span>
               </label>
            </td>
         </tr>`)

      })

      $('[data-toggle="tooltip"]').tooltip()

      $('[data-status-btn]').on('click', function() {
         const id = parseInt($(this).attr('data-id'))
         const status = $(this).is(':checked')
         updateStatus(id, status, $(this))
      })

   }

   async function updateStatus(userId, active, btn) {

      try {
         await UserService.updateActive(userId, active)
         alerty.show('success', 'Ebaaaaaaaa!', 'Usuário atualizado com sucesso!')
         renderPage()
      } catch {
         btn.prop('checked', !active)
      }

   }


   async function renderPage() {

      loader.show()

      try {

         const users = await UserService.get()
         USERS_GLOBAL = users

         listUsers()

      } catch (error) {
         console.error(error);
      } finally {
         loader.hide()
      }

   }

   renderPage()

})
