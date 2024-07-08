@extends('admin.dashboard')
@section('title', 'Grupo de Peças')

@section('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/grupos-de-peca.css') }}">
@endsection

@section('content')

 <x-main-card>
      <div class="pl-3">
         <div class="d-block">
            <h2 class="middle-title mr-5">
               <i class="fas fa-cog"></i>
               Grupo
            </h2>

            <h2 class="middle-title">
               <i class="fas fa-tag"></i>
               Tipo de Peça
            </h2>
         </div>

         <form id="part-groups-form" class="table-wrapper">
            <table class="form-table">
               <thead>
                  <tr>
                     <td class="padding-td">
                        Categoria
                     </td>
                     <td>
                        Selecione o tipo abaixo.
                     </td>
                     <td></td>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
            <button id="btn-new-part-group" class="btn btn-purple" type="button">
               <i class="fas fa-plus"></i>
               Novo Grupo
            </button>
         </form>

         <div class="form-btns-bottom">
            <button type="submit" form="part-groups-form" id="btn-submit" class="btn btn-success mr-2">
               <i class="fas fa-check"></i>
               Salvar Alterações
            </button>
            <button class="btn btn-danger">
               <i class="fas fa-trash"></i>
               Descartar Alterações
            </button>
         </div>
      </div>
   </x-main-card>

@endsection

@section('scripts')
   <script type="module" src="{{ asset('assets/js/pages/grupos-de-peca.js') }}"></script>
@endsection
