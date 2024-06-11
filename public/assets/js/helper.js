export function isEmailValid(email) {
   let re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   return re.test(String(email).toLowerCase());
}

/**
 * Para a função funcionar com a os selects da lib "bootstrap-select", você deve adicionar o atributo "data-selectpicker" nos elementos :D
 * E os editores da lib "quilljs", você deve adicionar o atributo "data-quilljs" :D
 */
export function allFieldsHaveValue(fieldsSelector, shouldCallAlert = false) {
   let inputs = document.querySelectorAll(fieldsSelector)
   let flag = true

   inputs.forEach(input => {
      let inputValue = String(input.value)
      const isSelectPicker = input.hasAttribute('data-selectpicker')
      const isQuillEditor = input.hasAttribute('data-quilljs')

      if (isSelectPicker) {

         const $input = $(input)
         inputValue = $input.val()

         if ((Array.isArray(inputValue) && inputValue.length < 1) || inputValue == '' || !inputValue) {
            $input.selectpicker('setStyle', 'is-invalid', 'add')
            flag = false
         } else {
            $input.selectpicker('setStyle', 'is-invalid', 'remove')
         }

      } else if (isQuillEditor) {

         const $input = $(input)
         inputValue = $input.text()

         if (inputValue.trim() == '') {
            $input.css("border", "1px solid red")
            flag = false
         } else {
            $input.css("border", "1px solid #ccc")
         }

      } else {

         if (inputValue.trim() == '' || (input.getAttribute('type') === 'email' && !isEmailValid(inputValue))) {
            input.classList.add('is-invalid')
            flag = false
         } else {
            input.classList.remove('is-invalid')
         }

      }

   })

   if (!flag && shouldCallAlert)
      callAlert('danger', 'Quase lá!', 'Por favor, preencha todos os campos', 3)

   return flag
}



export function generatePassword() {
   const length = 8;
   const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+[]{}|;:,.<>?";

   let password = "";
   let hasUppercase = false;
   let hasLowercase = false;
   let hasSpecialChar = false;
   let hasNumber = false;

   while (password.length < length || !hasUppercase || !hasLowercase || !hasSpecialChar || !hasNumber) {
      const randomChar = charset[Math.floor(Math.random() * charset.length)];
      password += randomChar;

      if (/[A-Z]/.test(randomChar)) {
         hasUppercase = true;
      } else if (/[a-z]/.test(randomChar)) {
         hasLowercase = true;
      } else if (/[^a-zA-Z0-9]/.test(randomChar)) {
         hasSpecialChar = true;
      } else if (/\d/.test(randomChar)) {
         hasNumber = true;
      }
   }

   return password;
}

export function waitForm(jqueryButtonElement, shouldWait = true, text = false, icon = false) {

   if (shouldWait) {
      if (!text) text = ''

      jqueryButtonElement.attr('data-first-html', jqueryButtonElement.html()).prop('disabled', true)
      jqueryButtonElement.html('<i class="fas fa-circle-notch fa-spin"></i> ' + text)

      $('div[role="tooltip"]').remove()

   } else {
      let firstHtml = jqueryButtonElement.attr('data-first-html')

      if (!text && firstHtml)
         jqueryButtonElement.html(firstHtml)
      else
         jqueryButtonElement.html(text + ' ' + (icon ? `<i class="ml-1 ${icon}"</i>` : ''))

      jqueryButtonElement.prop('disabled', false)
   }
}

export function oneRowTableMessage(jqueryElement, HTMLMessage) {
   HTMLMessage = HTMLMessage.toString()

   let headerSize = jqueryElement.find('thead').find('td, th').length
   if (headerSize == 0) return

   let tbody = jqueryElement.find('tbody')
   if (tbody.length == 0) return

   tbody.html(`<tr data-one-row-table-message><td colspan="${headerSize}" class="text-center">${HTMLMessage}</td></tr>`)
}

export function skeletonTable(jqueryElement, numRows = 0) {
   let headerSize = jqueryElement.find('thead').find('td, th').length
   if (headerSize == 0) return

   let tbody = jqueryElement.find('tbody')
   if (tbody.length == 0) return
   tbody.html('')

   let td = '<tr>'
   for (let i = 0; i < headerSize; i++)
      td += '<td><p class="skeleton skeleton-text"></p></td>'

   td += '</tr>'

   numRows = numRows != 0 ? numRows : Math.floor(Math.random() * 10) + 1
   for (let i = 0; i < numRows; i++)
      tbody.append(td)
}

export function onlyNumbers(string) {
   return String(string).replace(/\D/g, "")
}

export function onlyNumbersOrDots(string) {
   return String(string).replace(/[^0-9.]/g, "")
}

export function isValidPassword(password) {
   password = String(password)

   if (password.length < 8) {
      return false;
   }

   if (password.length > 100) {
      return false;
   }

   var mixedCaseRegex = /(?=.*[a-z])(?=.*[A-Z])/;
   if (!mixedCaseRegex.test(password)) {
      return false;
   }

   var numbersRegex = /\d/;
   if (!numbersRegex.test(password)) {
      return false;
   }

   var symbolsRegex = /[-!@$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/;
   if (!symbolsRegex.test(password)) {
      return false;
   }

   return true;
}

export function isCpf(value) {
   return String(value).length === 11
}

export const masks = {

   phone: {
      apply: function (phone) {
         if(!phone)
            return ''

         phone = phone.replace(/\D/g, "")
         phone = phone.replace(/^0/, "")

         if (phone.length > 10) {
            phone = phone.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3")
         } else if (phone.length > 5) {
            phone = phone.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3")
         } else if (phone.length > 2) {
            phone = phone.replace(/^(\d\d)(\d{0,5})/, "($1) $2")
         } else {
            phone = phone.replace(/^(\d*)/, "($1")
         }
         return phone
      },
      remove: function (phone) {
         return phone.replace(/\D/g, "").substr(0, 11)
      }
   },

   cnpj: {
      apply: function (cnpj) {
         cnpj = cnpj.replace(/\D/g, "").substr(0, 14)
         cnpj = cnpj.replace(/^(\d{2})(\d)/, "$1.$2")
         cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
         cnpj = cnpj.replace(/\.(\d{3})(\d)/, ".$1/$2")
         cnpj = cnpj.replace(/(\d{4})(\d)/, "$1-$2")
         return cnpj
      },
      remove: function (cnpj) {
         return cnpj.replace(/\D/g, "").substr(0, 14)
      }
   },

   cpf: {
      apply: function (cpf) {
         cpf = cpf.replace(/\D/g, "").substr(0, 11);
         cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
         cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
         cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
         return cpf;
      },
      remove: function (cpf) {
         return cpf.replace(/\D/g, "").substr(0, 11);
      }
   },

   zipCode: {
      apply: function (zipCode) {
         zipCode = zipCode.replace(/\D/g, "").substr(0, 8)
         zipCode = zipCode.replace(/^(\d{5})(\d)/, "$1-$2")
         return zipCode
      },
      remove: function (zipCode) {
         return zipCode.replace(/\D/g, "").substr(0, 8)
      }
   },

   money: {
      apply: function (money) {
         if (typeof money == "number")
            money = String(money.toFixed(2))
         else if (!money)
            return


         money = money.replace(/\D/g, ""); // Remove todos os não dígitos
         money = money.replace(/(\d+)(\d{2})$/, "$1,$2"); // Adiciona a parte de centavos
         money = money.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Adiciona pontos a cada três dígitos
         return money
      },
      remove: function (money) {
         money = String(money)

         if (money === "") {
            money = 0
         } else {
            money = money.replaceAll(".", "")
            money = money.replace(",", ".")
            money = parseFloat(money)
         }

         return money
      }
   },

   cpfCnpj: {
      apply: function (value) {
         let maskedValue = value.replace(/\D/g, "").substr(0, 11);
         maskedValue = maskedValue.replace(/(\d{3})(\d)/, "$1.$2");
         maskedValue = maskedValue.replace(/(\d{3})(\d)/, "$1.$2");
         maskedValue = maskedValue.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

         if (value.length > 14) { //14 is the expected size of a cpf with masks on
            maskedValue = value.replace(/\D/g, "").substr(0, 14);
            maskedValue = maskedValue.replace(/^(\d{2})(\d)/, "$1.$2");
            maskedValue = maskedValue.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            maskedValue = maskedValue.replace(/\.(\d{3})(\d)/, ".$1/$2");
            maskedValue = maskedValue.replace(/(\d{4})(\d)/, "$1-$2");
         }

         return maskedValue;
      },
      remove: function (value) {
         return value.replace(/\D/g, "").substr(0, value.length > 11 ? 14 : 11);
      }
   }

}

export const dateHelper = {

   currentDate: new Date(),

   formatFromDateObj: {

      /**
       * @param {Date} dateObj
       */
      en: function(dateObj) {
         var year  = dateObj.getFullYear()
         var month = (dateObj.getMonth() + 1).toString().padStart(2, '0')
         var day   = dateObj.getDate().toString().padStart(2, '0')
         return `${year}-${month}-${day}`
      },

      /**
       * @param {Date} dateObj
       */
      br: function(dateObj) {
         var year  = dateObj.getFullYear()
         var month = (dateObj.getMonth() + 1).toString().padStart(2, '0')
         var day   = dateObj.getDate().toString().padStart(2, '0')
         return `${day}/${month}/${year}`
      },

   },

   getFirstDayOfCurrentMonth: function() {
      const firstDayOfMonth = new Date(dateHelper.currentDate.getFullYear(), dateHelper.currentDate.getMonth(), 1)
      return dateHelper.formatFromDateObj.en(firstDayOfMonth)
   },

   getLastDayOfCurrentMonth: function() {
      const lastDayOfMonth = new Date(dateHelper.currentDate.getFullYear(), dateHelper.currentDate.getMonth() + 1, 0)
      return dateHelper.formatFromDateObj.en(lastDayOfMonth)
   },


}

export const loader = {

   show: function() {
      $('#main-card-loader').addClass('show')
      },
   hide: function() {
      $('#main-card-loader').removeClass('show')
   }

}

export const alerty = {
   setProgress: function (duration = 2, pageAlert, pageAlertProgressBar) {
      let availableDurations = [0, 1, 2, 3, 4, 5]
      if (!availableDurations.includes(duration))
         duration = 2

      pageAlertProgressBar.removeClass('').addClass(`progress${duration}`)
      setTimeout(() => {
         pageAlert.fadeOut(200)
         setTimeout(() => pageAlert.remove(), 200)
      }, duration * 1000)
   },

   /**
    * @param {("danger"|"warning"|"success")} type
    * @param {(1|2|3|4|5)} duration
    */
   show: function (type, title, desc = '', duration = 2) {

      if (!['warning', 'danger', 'success'].includes(type)) {
         console.log('Alerta inválido!')
         return false
      }

      let elementRef = 'rd-' + Math.floor(Math.random() * 1000)

      $('#alerty').append(`
         <div class="page-alert ${elementRef}">
            <i class="alert-icon fas ${elementRef}"></i>
            <div class="texts">
               <p class="title">${title}</p>
               <p class="desc">${desc ?? ''}</p>
            </div>
            <i class="fas fa-times close-alert ${elementRef}"></i>

            <div class="alert-progress-bar ${elementRef}"></div>
         </div>
      `)

      let pageAlert = $(`.page-alert.${elementRef}`)
      let pageAlertIcon = $(`.alert-icon.${elementRef}`)
      let pageAlertProgressBar = $(`.alert-progress-bar.${elementRef}`)

      pageAlert.removeClass('success danger warning')
      pageAlertIcon.removeClass('fa-check fa-exclamation-circle')

      pageAlert.addClass(type)

      if (type == 'success')
         pageAlertIcon.addClass('fa-check')
      else if (type == 'danger')
         pageAlertIcon.addClass('fa-bug')
      else
         pageAlertIcon.addClass('fa-exclamation-circle')

      if (duration != 0)
         alerty.setProgress(duration, pageAlert, pageAlertProgressBar)

      pageAlert.click(() => closeAlert(elementRef))
   },

   hide: function (elementRef = false) {
      if (!elementRef) {
         $(`.page-alert`).fadeOut(100)
      } else {
         $(`.page-alert.${elementRef}`).fadeOut(100)
      }
      setTimeout(() => $(`.page-alert.${elementRef}`).remove(), 200)
   }

}
