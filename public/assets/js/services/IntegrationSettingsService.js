import { API_URL } from "../config.js"

/**
 * @typedef {object} IntegrationSettings
 * @property {string} linx_user
 * @property {string} linx_password
 * @property {string} linx_auth_key
 * @property {string} linx_stock_key
 * @property {string} cilia_token
 * @property {boolean} enabled
 * @property {number} interval
 */

/**
 * @typedef {object} IntegrationExecution
 * @property {number} id
 * @property {number|null} linx_status_code
 * @property {number|null} cilia_status_code
 * @property {string|null} error
 * @property {boolean} forced_execution
 * @property {boolean} has_cilia_payload
 * @property {string} duration
 * @property {string} created_at
 */


const URL = `${API_URL}/integration-settings`
const URL_EXECUTIONS = `${API_URL}/integrations/executions`

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

/**
 * @returns {Promise<IntegrationExecution[]>}
 */
export async function getLogs() {

   const config = {
      method: 'GET',
      url: `${URL_EXECUTIONS}`,
   }

   return $.ajax(config)
}

export async function createExectuion(btn) {

   const config = {
      method: 'POST',
      url: `${URL_EXECUTIONS}`,
      waitButton: btn
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
   get, update, getLogs, createExectuion
}
