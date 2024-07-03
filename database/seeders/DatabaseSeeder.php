<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      \App\Models\User::create([
         'name' => 'Admin Faberge',
         'email' => 'admin@faberge.com.br',
         'password' => Hash::make('senha123@@')
      ]);

      \App\Models\IntegrationSettings::create([
         'enabled' => true,
         'interval' => 30,
         'user_id' => 1,
         'linx_user' => 'FabergeLinx',
         'linx_password' => Crypt::encrypt('senha123@@'),
         'cilia_token' => Crypt::encrypt(Str::random(40))
      ]);

      \App\Models\Company::create([
         'name' => 'Grupo Faberge',
         'cnpj' => '06900979000211',
         'primary' => true,
         'active' => true,
         'user_id' => 1,
      ]);

      \App\Models\Company::create([
         'name' => 'Grupo Faberge - Maranhão',
         'cnpj' => '06900979000212',
         'primary' => false,
         'active' => true,
         'user_id' => 1,
      ]);
   }
}
