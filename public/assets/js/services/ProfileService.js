import { API_URL } from "../config.js"

/**
 * @typedef {object} Profile
 * @property {number} id
 * @property {string} email
*/

const URL = `${API_URL}/me`

/**
 * @returns {Promise<Profile>}
 */
export function get() {
   const config = {
      method: 'GET',
      url: `${URL}`,
   }

   return $.ajax(config)
}

export function update(data, btn) {
   const config = {
      method: 'PUT',
      url: `${URL}`,
      data: JSON.stringify(data),
      waitButton: btn,
   }

   return $.ajax(config)
}

export default {
   get, update
}

