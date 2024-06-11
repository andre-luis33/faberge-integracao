jQuery(function() {

   const sidebar = $('.sidebar')
   const sidebarBtn = $('#sidebar-btn')

   sidebarBtn.on('click', () => {
      sidebar.toggleClass('closed')
   })

   $('[data-trash-changes]').on('click', () => {
      window.location.reload()
   })


   // $('[data-toggle="tooltip"]').tooltip()

})
