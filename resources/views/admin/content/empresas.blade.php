@extends('admin.dashboard')
@section('title', 'Empresas')

@section('content')

   <x-main-card>

      <div class="d-flex justify-content-between align-items-center">
         <h2 class="side-title">
            Empresas cadastradas <span id="companies-count"></span>
         </h2>

         <button class="btn btn-success mr-4" data-toggle="modal" data-target="#companies-modal">
            <i class="fas fa-plus"></i>
            Nova Empresa
         </button>
      </div>

      <p class="text-purple-primary mt-3 ml-4">
         Veja todas as empresas que você possui relacionadas ao seu usuário.
      </p>

      <div class="table-responsive px-4">
         <table class="table table-hover" id="companies-table">
            <thead>
               <tr>
                  <td>Nome</td>
                  <td>CNPJ</td>
                  <td>Última Integração</td>
                  <td>Cadastrada em</td>
                  <td>Editar</td>
               </tr>
            </thead>
            <tbody>
            </tbody>
         </table>
      </div>

   </x-main-card>

   <x-modal id="companies-modal" title="Empresa" size="md">
      <x-slot name="body">
         <form id="companies-form">
            <input type="hidden" id="input-id">

            <div class="input-wrapper">
               <i class="fas fa-file-signature"></i>
               <input class="form-control" type="text" id="input-cnpj" placeholder="CNPJ" data-required data-mask="cnpj">
            </div>

            <div class="input-wrapper">
               <i class="fas fa-signature"></i>
               <input class="form-control" type="text" id="input-name" placeholder="Nome" data-required>
            </div>

            <p>Empresa primária?</p>
            <input type="radio" name="primary" id="input-true"> <label for="input-true">Sim</label>
            <input type="radio" name="primary" id="input-false" checked> <label for="input-false">Não</label>
         </form>

      </x-slot>

      <x-slot name="footer">
         <button class="btn btn-secondary">
            Cancelar
         </button>
         <button class="btn btn-purple" type="submit" form="companies-form" id="btn-submit">
            Salvar Alterações
         </button>
      </x-slot>
   </x-modal>

@endsection

@section('scripts')
   <script type="module" src="{{asset('assets/js/pages/empresas.js?1')}}"></script>
@endsection

