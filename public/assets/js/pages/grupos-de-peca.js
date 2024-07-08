import { alerty, allFieldsHaveValue, loader } from "../helper.js"
import PartGroupService from "../services/PartGroupService.js"

jQuery(function() {

   const table = $('.form-table')

   const form = $('#part-groups-form')
   const btnSubmit = $('#btn-submit')

   const btnNewPartGroup = $('#btn-new-part-group')

   btnNewPartGroup.on('click', () => {
      appendPartGroupRow('Grupo A')
   })

   form.on('submit', async e => {

      e.preventDefault()

      const trs = table.find('tbody').find('tr')
      const payload = []
      let allOk = true

      trs.each(function() {
         const input = $(this).find('input')
         const select = $(this).find('select')

         const category = input.val()
         const type = select.val()

         if(category === '') {
            input.addClass('is-invalid')
            allOk = false
         } else {
            input.removeClass('is-invalid')
         }

         if(type === '') {
            select.addClass('is-invalid')
            allOk = false
         } else {
            select.removeClass('is-invalid')
         }

         if(!allOk)
            return

         payload.push({
            category, type
         })
      })

      if(!allOk) {
         alerty.show('danger', 'Ah não!', 'Por favor, preencha todas as categorias e seus tipos, ou exclua as que não possuem valor', 4)
         return
      }

      try {

         await PartGroupService.update(payload, btnSubmit)
         alerty.show('success', 'Sucesso!', 'Grupo de Peças atualizados')

      } catch {

      }

   })

   function appendPartGroupRow(category, type) {
      const rowId = `row-${Math.floor(Math.random() * 1000000)}`

      table.find('tbody').append(`
         <tr>
            <td>
               <div class="input-wrapper">
                  <i class="fas fa-wrench"></i>
                  <input class="form-control" type="text" placeholder="Grupo A" value="${category}">
               </div>
            </td>
            <td>
               <select class="form-control" data-required>
                  <option value="" hidden>Nenhum</option>
                  <option ${type === 'Genuína' ? 'selected' : ''} value="Genuína">Genuína</option>
                  <option ${type === 'Original' ? 'selected' : ''} value="Original">Original</option>
                  <option ${type === 'Verde' ? 'selected' : ''} value="Verde">Verde</option>
                  <option ${type === 'Outras Fontes' ? 'selected' : ''} value="Outras Fontes">Outras Fontes</option>
               </select>
            </td>
            <td>
               <button class="btn btn-danger" data-btn-delete-row type="button">
                  <i class="fas fa-trash"></i>
               </button>
            </td>
         </tr>
      `)

      $('[data-btn-delete-row]').off('click').on('click', function() {
         $(this).parent().parent().remove()
      })
   }

   async function renderPage() {

      try {

         loader.show()
         const partGroups = await PartGroupService.get()

         if(partGroups.length < 1) {
            alerty.show('warning', 'Atenção!', 'Você não possui nenhum grupo de peças cadastrado. Dessa forma, a integração não irá funcionar corretamente.', 0)
            return
         }

         partGroups.forEach(partGroup => {
            const { type, category } = partGroup
            appendPartGroupRow(category, type)
         })

      } catch (e) {
         console.error(e)
      } finally {
         loader.hide()
      }

   }

   renderPage()

})
