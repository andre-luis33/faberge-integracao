@extends('admin.dashboard')
@section('title', 'Integração')

@php

   $executionsIntervals = [
      15,
      30,
      45,
      60
   ];

   $selectedInterval = 45;

@endphp

@section('content')

   <x-main-card>

      <div class="wrapper">
         <h2 class="side-title">
            Status
         </h2>

         <div class="pl-4 my-3">
            <p class="text-purple-primary mb-1">
               Selecione se a integração esta ativa ou não.
            </p>

            <label class="switch">
               <input type="checkbox" id="status-btn">
               <span class="slider round"></span>
            </label>
         </div>
      </div>

      <div class="wrapper pb-3">
         <h2 class="side-title">
            Período de Atualização
         </h2>

         <div class="pl-4 mt-3">
            <p class="text-purple-primary">
               Selecione uma opção abaixo.
            </p>

            @foreach($executionsIntervals as $interval)
               <button class="btn btn-outline-purple" data-interval="{{$interval}}">
                  {{$interval}} min
               </button>
            @endforeach
         </div>
      </div>

      <div class="wrapper mt-4">
         <h2 class="side-title">
            Integração Linx
         </h2>

         <div class="pl-4 mt-3">
            <p class="text-purple-primary">
               Você deve fornecer o usuário e senha da API da Linx para que os dados de estoque sejam obtidos.
            </p>

            <div class="form-group">
               <div class="form-row">
                  <div class="col-md-3">
                     <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input class="form-control" type="text" id="linx-user" placeholder="Usuário" data-required>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input class="form-control" type="password" id="linx-password" placeholder="Senha" data-required>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="wrapper mt-4">
         <h2 class="side-title">
            Integração Cilia
         </h2>

         <div class="pl-4 mt-3">
            <p class="text-purple-primary">
               Você deve fornecer o token de acesso da API da Cilia para que os dados de estoque sejam enviados.
            </p>

            <div class="form-group">
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input class="form-control" type="text" id="cilia-token" placeholder="Token de Acesso" data-required>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>


      <div class="form-btns-bottom">
         <button id="btn-submit" class="btn btn-success mr-2">
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
   <script type="module" src="{{asset('assets/js/pages/integracao.js')}}"></script>
@endsection
