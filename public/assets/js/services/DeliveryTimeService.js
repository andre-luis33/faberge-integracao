import { API_URL } from "../config.js"

/**
 * @typedef {object} DeliveryTime
 * @property {string} uf
 * @property {number} days
 */


const URL = `${API_URL}/delivery-times`

/**
 * @returns {Promise<DeliveryTime[]>}
 */
export async function get() {

   const config = {
      method: 'GET',
      url: `${URL}`,
   }

   return $.ajax(config)
}

export async function update(data, btn) {

   const config = {
      method: 'PUT',
      url: `${URL}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export default {
   get, update
}
