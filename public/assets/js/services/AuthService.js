import { API_URL } from "../config.js"


const URL = `${API_URL}/login`

/**
 * @returns {Promise<DeliveryTime[]>}
 */
export function login(data, btn) {

   const config = {
      method: 'POST',
      url: `${URL}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export default {
   login
}
