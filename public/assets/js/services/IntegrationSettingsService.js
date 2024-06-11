import { API_URL } from "../config.js"

/**
 * @typedef {object} IntegrationSettings
 * @property {boolean} enabled
 * @property {number} interval
 */


const URL = `${API_URL}/integration-settings`

/**
 * @returns {Promise<IntegrationSettings>}
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
