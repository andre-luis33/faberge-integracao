import { API_URL } from "../config.js"

export async function updateSidebarStatus(closed) {

   const config = {
      method: 'PUT',
      url: `${API_URL}/session/sidebar`,
      data: JSON.stringify({ closed }),
   }

   return $.ajax(config)
}

export default {
   updateSidebarStatus
}
