@extends('admin.dashboard')
@section('title', 'Grupo de Peças')

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

         <div class="table-wrapper">
            <table class="form-table">
               <thead>
                  <tr>
                     <td class="padding-td">
                        Categoria
                     </td>
                     <td>
                        Selecione o tipo abaixo.
                     </td>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td data-category="Grupo A">Grupo A</td>
                     <td>
                        <select name="" id="" class="form-control" data-required>
                           <option value="">Nenhum</option>
                           <option value="">Genuína</option>
                           <option value="">Original</option>
                           <option value="">Verde</option>
                           <option value="">Outras Fontes</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td data-category="Grupo B">Grupo B</td>
                     <td>
                        <select name="" id="" class="form-control" data-required>
                           <option value="">Nenhum</option>
                           <option value="">Genuína</option>
                           <option value="">Original</option>
                           <option value="">Verde</option>
                           <option value="">Outras Fontes</option>
                        </select>
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>

         <div class="form-btns-bottom">
            <button class="btn btn-success mr-2">
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
