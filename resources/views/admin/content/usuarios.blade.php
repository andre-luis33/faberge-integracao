@extends('admin.dashboard')
@section('title', 'Usuários')

@section('content')

   <x-main-card>

      <div class="d-flex justify-content-between align-items-center">
         <h2 class="side-title">
            Usuários do Sistema <span id="users-count"></span>
         </h2>

         <button class="btn btn-success mr-4" data-toggle="modal" data-target="#users-modal">
            <i class="fas fa-plus"></i>
            Novo Usuário
         </button>
      </div>

      <p class="text-purple-primary mt-3 ml-4">
         Veja todos os usuários cadastrados no sistema.
      </p>

      <div class="table-responsive px-4">
         <table class="table table-hover" id="users-table">
            <thead>
               <tr>
                  <td>Nome</td>
                  <td>E-mail</td>
                  <td>Cadastrado em</td>
                  <td>Empresas</td>
                  <td>Ativo</td>
               </tr>
            </thead>
            <tbody>
            </tbody>
         </table>
      </div>

   </x-main-card>

   <x-modal id="users-modal" title="Novo Usuário" size="md">
      <x-slot name="body">
         <form id="users-form" novalidate>

            <div class="input-wrapper">
               <i class="fas fa-signature"></i>
               <input class="form-control" type="text" id="input-name" placeholder="Nome" data-required>
            </div>

            <div class="input-wrapper">
               <i class="fas fa-at"></i>
               <input class="form-control" type="text" id="input-email" placeholder="E-mail" data-required>
            </div>

            <div class="input-wrapper">
               <i class="fas fa-lock"></i>
               <input class="form-control" type="password" id="input-password" placeholder="Senha" data-required>
            </div>

            <div class="input-wrapper">
               <i class="fas fa-image"></i>
               <input class="form-control" type="url" id="input-logo-url" placeholder="URL Logo" data-required>
            </div>

            <hr>

            <h5 class="mb-2">Empresa Primária</h5>

            <p class="text-purple-primary">
               O usuário precisa de uma empresa para poder se logar.
            </p>

            <div class="input-wrapper">
               <i class="fas fa-signature"></i>
               <input class="form-control" type="text" id="input-company-name" placeholder="Nome" data-required>
            </div>

            <div class="input-wrapper">
               <i class="fas fa-file-signature"></i>
               <input class="form-control" type="text" id="input-company-cnpj" placeholder="CNPJ" data-mask="cnpj" data-required>
            </div>
         </form>

      </x-slot>

      <x-slot name="footer">
         <button class="btn btn-secondary" data-dismiss="modal">
            Cancelar
         </button>
         <button class="btn btn-purple" type="submit" form="users-form" id="btn-submit">
            Salvar Alterações
         </button>
      </x-slot>
   </x-modal>

   <x-modal id="companies-modal" title="Empresas do Usuário" size="lg">
      <x-slot name="body">
         <div class="alert alert-info text-center">
            Visualize todas as empresas que o usuário possui, e ative/desative elas. <br> As integrações das empresas desativadas não serão executadas.
         </div>

         <div class="table-responsive">
            <table id="companies-table" class="table table-hover">
               <thead>
                  <tr>
                     <td>Empresa.Revenda</td>
                     <td>Nome</td>
                     <td>CNPJ</td>
                     <td>Ativa</td>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>

      </x-slot>

      <x-slot name="footer">
         <button class="btn btn-secondary" data-dismiss="modal">
            Fechar
         </button>
      </x-slot>
   </x-modal>

@endsection

@section('scripts')
   <script type="module" src="{{asset('assets/js/pages/usuarios.js?1')}}"></script>
@endsection

