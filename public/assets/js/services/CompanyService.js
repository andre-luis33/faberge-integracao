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

/**
 * @typedef {object} LastExecution
 * @property {number} company_id
 * @property {boolean} last_execution_successful
 * @property {string|null} last_execution_at
 */

const URL = `${API_URL}/companies`

/**
 * @returns {Promise<Company[]>}
 */
export function get() {

   const config = {
      method: 'GET',
      url: `${URL}`,
   }

   return $.ajax(config)
}

/**
 * @returns {Promise<LastExecution[]>}
 */
export function getLastExecutions() {
   const config = {
      method: 'GET',
      url: `${URL}/integrations/executions/latest`,
   }

   return $.ajax(config)
}

export function create(data, btn) {

   const config = {
      method: 'POST',
      url: `${URL}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export function update(companyId, data, btn) {

   const config = {
      method: 'PUT',
      url: `${URL}/${companyId}`,
      data: JSON.stringify(data),
      waitButton: btn
   }

   return $.ajax(config)
}

export default {
   get, create, update, getLastExecutions
}
