import { API_URL } from "../config.js"

/**
 * @typedef {object} Company
 * @property {number} id
 * @property {string} cnpj
 * @property {string} name
 * @property {boolean} primary
 * @property {boolean} active
 * @property {boolean} last_execution_successful
 * @property {string|null} last_execution_at
 * @property {string} created_at
 * @property {string} updated_at
 */

const URL = `${API_URL}/companies`

/**
 * @returns {Promise<Company[]>}
 */
export async function get() {

   const config = {
      method: 'GET',
      url: `${URL}`,
   }

   return $.ajax(config)
}

export async function create(data, btn) {

   const config = {
      method: 'POST',
      url: `${URL}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export async function update(companyId, data, btn) {

   const config = {
      method: 'PUT',
      url: `${URL}/${companyId}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export default {
   get, create, update
}
