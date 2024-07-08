import { alerty } from "./helper.js"
import SessionService from "./services/SessionService.js"

jQuery(function() {

   const sidebar = $('.sidebar')
   const sidebarBtn = $('#sidebar-btn')

   sidebarBtn.on('click', () => {
      const isClosed = sidebar.hasClass('closed')
      if(isClosed) {
         sidebar.removeClass('closed')
      } else {
         sidebar.addClass('closed')
      }

      SessionService.updateSidebarStatus(!isClosed)
   })

   $('[data-trash-changes]').on('click', () => {
      window.location.reload()
   })


   const changeCompanyForm = $('#change-company-form')
   const changeCompanyBtn = $('#change-company-btn-submit')
   const selectCurrentCompany = $('#select-current-company')

   changeCompanyForm.on('submit', async e => {
      e.preventDefault()
      const companyId = selectCurrentCompany.val()

      try {

         await SessionService.updateCurrentCompany(companyId, changeCompanyBtn)
         alerty.show('success', 'Sucesso!', 'Você trocou de empresa', 1)

         setTimeout(() => {
            window.location.reload()
         }, 500)

      } catch (error) {
         console.error(error)
      }

   })

   $('[data-toggle="tooltip"]').tooltip()
})
