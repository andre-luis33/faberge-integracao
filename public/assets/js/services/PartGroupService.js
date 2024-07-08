import { API_URL } from "../config.js"

/**
 * @typedef {object} PartGroup
 * @property {string} category
 * @property {string} type
 */

const URL = `${API_URL}/part-groups`

/**
 * @returns {Promise<PartGroup[]>}
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

