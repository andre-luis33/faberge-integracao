<?php

namespace App\Http\Controllers;

use App\Models\IntegrationSettings;
use App\Utils\Masks;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;

class IntegrationSettingsController extends Controller
{
   private IntegrationSettings $integrationSettings;

   public function __construct(IntegrationSettings $integrationSettings) {
      $this->integrationSettings = $integrationSettings;
      $this->setSession();

      // remove in production
      Sleep::sleep(.3);
   }

   public function index() {
      $companyId = $this->session->company->id;
      $settings = $this->integrationSettings
         ->select(['interval', 'enabled', 'linx_user', 'linx_password', 'linx_auth_key', 'linx_stock_key', 'cilia_token'])
         ->where('company_id', $companyId)
         ->first();

      if(!$settings)
         return response()->json($settings);

      $linxPassword = Crypt::decrypt((string) $settings->linx_password);
      $linxAuthKey = Crypt::decrypt((string)  $settings->linx_auth_key);
      $linxStockKey = Crypt::decrypt((string) $settings->linx_stock_key);
      $ciliaToken = Crypt::decrypt((string)   $settings->cilia_token);

      $settings->linx_password  = $linxPassword ? Masks::secret($linxPassword) : null;
      $settings->linx_auth_key  = $ciliaToken   ? Masks::secret($linxAuthKey) : null;
      $settings->linx_stock_key = $linxPassword ? Masks::secret($linxStockKey) : null;
      $settings->cilia_token    = $ciliaToken   ? Masks::secret($ciliaToken) : null;

      return response()->json($settings);
   }

   public function update(Request $request) {

      $data = $request->validate([
         'enabled'        => 'required|boolean',
         'interval'       => 'required|integer|in:15,30,45,60',
         'linx_user'      => 'nullable|string|max:50',
         'linx_password'  => 'nullable|string|max:50',
         'linx_auth_key'  => 'nullable|string|max:50',
         'linx_stock_key' => 'nullable|string|max:50',
         'cilia_token'    => 'nullable|string|max:100',
      ]);

      $data = $this->encryptOrUnsetKey($data, 'linx_password', $request);
      $data = $this->encryptOrUnsetKey($data, 'linx_auth_key', $request);
      $data = $this->encryptOrUnsetKey($data, 'linx_stock_key', $request);
      $data = $this->encryptOrUnsetKey($data, 'cilia_token', $request);

      $companyId = $this->session->company->id;

      try {

         if($this->integrationSettings->existsByCompanyId($companyId)) {
            $this->integrationSettings
               ->where('company_id', $companyId)
               ->update($data);

         } else {
            $data['company_id'] = $companyId;

            $this->integrationSettings
               ->create($data);
         }

         return response(status: 204);

      } catch (Exception $e) {
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }
   }


   private function encryptOrUnsetKey(array $data, string $key, Request $request): array {
      if(!isset($data[$key]))
         unset($data[$key]);
      else
         $data[$key] = Crypt::encrypt($request->input($key));

      return $data;
   }
}
