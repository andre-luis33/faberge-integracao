@extends('admin.dashboard')
@section('title', 'Perfil')

@section('content')

   <x-main-card>

      <div class="d-flex justify-content-between align-items-center">
         <h2 class="side-title">
            Editar Informações
         </h2>

      </div>

      <form id="profile-form" class="form-group pl-4 mt-3">
         <p class="text-purple-primary">
            Essas informações são utilizadas no seu login. Não é possível consultar a senha atual, somente a redefinir.
         </p>

         <div class="form-row">
            <div class="col-md-6">
               <div class="input-wrapper">
                  <i class="fas fa-user"></i>
                  <input class="form-control" type="text" id="email" placeholder="E-mail" data-required>
               </div>
            </div>
            <div class="col-md-6">
               <div class="input-wrapper">
                  <i class="fas fa-lock"></i>
                  <input class="form-control" type="password" id="password" placeholder="Senha. Não digite nada para manter a atual">
               </div>
            </div>
         </div>
      </form>


      <div class="form-btns-bottom">
         <button id="btn-submit" class="btn btn-success mr-2" form="profile-form">
            <i class="fas fa-check"></i>
            Salvar Alterações
         </button>
         <button class="btn btn-danger" data-trash-changes>
            <i class="fas fa-trash"></i>
            Descartar Alterações
         </button>
      </div>

   </x-main-card>

@endsection

@section('scripts')
   <script type="module" src="{{asset('assets/js/pages/perfil.js')}}"></script>
@endsection

