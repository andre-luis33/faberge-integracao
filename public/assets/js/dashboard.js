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


   // $('[data-toggle="tooltip"]').tooltip()

})
