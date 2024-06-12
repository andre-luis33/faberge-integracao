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
      $userId = $this->session->userId;
      $settings = $this->integrationSettings
         ->select(['interval', 'enabled', 'linx_user', 'linx_password', 'cilia_token'])
         ->where('user_id', $userId)
         ->first();

      $linxPassword = Crypt::decrypt($settings->linx_password);
      $ciliaToken = Crypt::decrypt($settings->cilia_token);

      $settings->linx_password = $linxPassword ? Masks::secret($linxPassword) : null;
      $settings->cilia_token = $ciliaToken ? Masks::secret($ciliaToken) : null;

      return response()->json($settings);
   }

   public function update(Request $request) {

      $data = $request->validate([
         'enabled'       => 'required|boolean',
         'interval'      => 'required|integer|in:15,30,45,60',
         'linx_user'     => 'nullable|string|max:50',
         'linx_password' => 'nullable|string|max:50',
         'cilia_token'   => 'nullable|string|max:100',
      ]);

      if(!$data['cilia_token'])
         unset($data['cilia_token']);
      else
         $data['cilia_token'] = Crypt::encrypt($request->input('cilia_token'));


      if(!$data['linx_password'])
         unset($data['linx_password']);
      else
         $data['linx_password'] = Crypt::encrypt($request->input('linx_password'));


      $userId = $this->session->userId;

      try {

         $this->integrationSettings
            ->where('user_id', $userId)
            ->update($data);

         return response(status: 204);

      } catch (Exception $e) {
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }
   }
}
