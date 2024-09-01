@extends('admin.dashboard')
@section('title', 'Integração')

@php

   $isWeekDay = date('w') > 0 && date('w') < 6;

   $executionsIntervals = [
      15,
      30,
      45,
      60
   ];

@endphp

@section('content')

   <x-main-card>

      <div class="wrapper">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="side-title">
               Status
            </h2>

            <button id="btn-executions" class="btn btn-success mr-3" data-toggle="modal" data-target="#integrations-modal">
               <i class="fas fa-stream"></i>
               Últimas Execuções
            </button>
         </div>

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
               Selecione uma opção abaixo. A execução automática roda de segunda a sexta, das 7:00 às 23:00
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
               Você deve fornecer as informações necessárias da Linx para que os dados de estoque sejam obtidos.
            </p>

            <div class="form-group">
               <div class="form-row">
                  <div class="col-md-2">
                     <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input class="form-control" type="text" id="linx-user" placeholder="Usuário" data-required data-toggle="tooltip" data-placement="bottom" title="O usuário usado para gerar um token de acesso (login)">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input class="form-control" type="text" id="linx-password" placeholder="Senha" data-required data-toggle="tooltip" data-placement="bottom" title="A senha usada para gerar um token de acesso (login)">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input class="form-control" type="text" id="linx-auth-key" placeholder="Subscription Key (Login)" data-required data-toggle="tooltip" data-placement="bottom" title="A subscription key usada para gerar um token de acesso (login)">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input class="form-control" type="text" id="linx-stock-key" placeholder="Subscription Key (Estoque)" data-required data-toggle="tooltip" data-placement="bottom" title="A subscription key usada para buscar os dados de estoque">
                     </div>
                  </div>
               </div>
            </div>

            <div class="form-group">
               <div class="form-row">
                  <div class="col-md-4">
                     <div class="input-wrapper">
                        <i class="fas fa-globe-americas"></i>
                        <input class="form-control" type="text" id="linx-environment" placeholder="Ambiente" data-required data-toggle="tooltip" data-placement="bottom" title="O ambiente usado para gerar um token de acesso (login) e buscar os dados de acesso">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="input-wrapper">
                        <i class="fas fa-building"></i>
                        <input class="form-control" type="text" id="linx-company" placeholder="Empresa" data-required data-toggle="tooltip" data-placement="bottom" title="A empresa usada para filtrar os dados de estoque">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="input-wrapper">
                        <i class="fas fa-certificate"></i>
                        <input class="form-control" type="text" id="linx-resale" placeholder="Revenda" data-required data-toggle="tooltip" data-placement="bottom" title="A revenda usada para filtrar os dados de estoque">
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
                  <div class="col-md-4">
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

   <x-modal id="integrations-modal" title="Últimas Execuções" size="xl">
      <x-slot name="body">
         <div class="alert alert-info text-center font-italic">
            @if($isWeekDay)
               Você está visualizando dados sobre as execuções de hoje, que acontecem entre 07:00 e 23:00.
            @else
               As execuções só acontecem de Segunda à Sexta, entre 07:00 e 23:00.
            @endif
         </div>

         <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Total de Execuções: <span id="executions-count"></span></h5>

            <button class="btn btn-purple" id="btn-execute-integration">
               <i class="fas fa-wrench"></i>
               Executar Agora
            </button>
         </div>

         <table id="executions-table" class="table table-hover">
            <thead>
               <tr>
                  <td>Data/Hora</td>
                  <td>Duração</td>
                  <td>Modo Execução</td>
                  <td>Status Linx</td>
                  <td>Status Cilia</td>
                  <td>Erro</td>
                  <td>CSV Cilia</td>
               </tr>
            </thead>
            <tbody>
            </tbody>
         </table>
      </x-slot>
      <x-slot name="footer">
         <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </x-slot>
   </x-modal>
@endsection

@section('scripts')
   <script type="module" src="{{asset('assets/js/pages/integracao.js')}}"></script>
@endsection
