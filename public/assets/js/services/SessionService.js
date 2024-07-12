import { API_URL } from "../config.js"

export async function updateCurrentCompany(companyId, btn) {

   const config = {
      method: 'PUT',
      url: `${API_URL}/session/active-company/${companyId}`,
      waitButton: btn
   }

   return $.ajax(config)
}

export async function updateSidebarStatus(closed) {

   const config = {
      method: 'PUT',
      url: `${API_URL}/session/sidebar`,
      data: JSON.stringify({ closed }),
   }

   return $.ajax(config)
}

export async function logout() {

   const config = {
      method: 'POST',
      url: `${API_URL}/logout`,
   }

   return $.ajax(config)
}

export default {
   updateSidebarStatus, updateCurrentCompany, logout
}
