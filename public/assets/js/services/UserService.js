import { API_URL } from "../config.js"

/**
 * @typedef {object} Company
 * @property {number} id
 * @property {string} name
 * @property {string} cnpj
 * @property {string} linx_company
 * @property {string} linx_resale
 * @property {boolean} active
 */

/**
 * @typedef {object} User
 * @property {number} id
 * @property {string} email
 * @property {string} name
 * @property {boolean} active
 * @property {Company[]} companies
 * @property {string} created_at
 */

const URL = `${API_URL}/users`

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

export async function updateActive(userId, active) {

   const config = {
      method: 'PUT',
      url: `${URL}/${userId}/active`,
      data: JSON.stringify({ active }),
   }

   return $.ajax(config)
}

export async function updateCompanyActive(userId, companyId, active) {

   const config = {
      method: 'PUT',
      url: `${URL}/${userId}/companies/${companyId}/active`,
      data: JSON.stringify({ active }),
   }

   return $.ajax(config)
}

export default {
   get, create, updateActive, updateCompanyActive
}
