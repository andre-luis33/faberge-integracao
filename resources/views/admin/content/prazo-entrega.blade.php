@extends('admin.dashboard')
@section('title', 'Prazo Entrega')

@section('content')

   <x-main-card>
      <div class="pl-3">
         <div class="d-block">
            <h2 class="middle-title mr-5">
               <i class="fas fa-map-marker-alt"></i>
               Estado
            </h2>

            <h2 class="middle-title">
               <i class="fas fa-clock"></i>
               Prazo Entrega
            </h2>
         </div>

         <form id="delivery-times-form" class="table-wrapper">
            <table class="form-table">
               <thead>
                  <tr>
                     <td class="bold">
                        UF
                     </td>
                     <td class="padding-td">
                        Estado
                     </td>
                     <td>
                        Insira os dias abaixo.
                     </td>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td class="bold">AC</td>
                     <td>Acre</td>
                     <td>
                        <input type="number" id="input-ac" name="ac" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">AL</td>
                     <td>Alagoas</td>
                     <td>
                        <input type="number" id="input-al" name="al" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">AP</td>
                     <td>Amapá</td>
                     <td>
                        <input type="number" id="input-ap" name="ap" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">AM</td>
                     <td>Amazonas</td>
                     <td>
                        <input type="number" id="input-am" name="am" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">BA</td>
                     <td>Bahia</td>
                     <td>
                        <input type="number" id="input-ba" name="ba" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">CE</td>
                     <td>Ceará</td>
                     <td>
                        <input type="number" id="input-ce" name="ce" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">DF</td>
                     <td>Distrito Federal</td>
                     <td>
                        <input type="number" id="input-df" name="df" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">ES</td>
                     <td>Espírito Santo</td>
                     <td>
                        <input type="number" id="input-es" name="es" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">GO</td>
                     <td>Goiás</td>
                     <td>
                        <input type="number" id="input-go" name="go" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">MA</td>
                     <td>Maranhão</td>
                     <td>
                        <input type="number" id="input-ma" name="ma" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">MT</td>
                     <td>Mato Grosso</td>
                     <td>
                        <input type="number" id="input-mt" name="mt" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">MS</td>
                     <td>Mato Grosso do Sul</td>
                     <td>
                        <input type="number" id="input-ms" name="ms" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">MG</td>
                     <td>Minas Gerais</td>
                     <td>
                        <input type="number" id="input-mg" name="mg" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">PA</td>
                     <td>Pará</td>
                     <td>
                        <input type="number" id="input-pa" name="pa" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">PB</td>
                     <td>Paraíba</td>
                     <td>
                        <input type="number" id="input-pb" name="pb" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">PR</td>
                     <td>Paraná</td>
                     <td>
                        <input type="number" id="input-pr" name="pr" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">PE</td>
                     <td>Pernambuco</td>
                     <td>
                        <input type="number" id="input-pe" name="pe" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">PI</td>
                     <td>Piauí</td>
                     <td>
                        <input type="number" id="input-pi" name="pi" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">RJ</td>
                     <td>Rio de Janeiro</td>
                     <td>
                        <input type="number" id="input-rj" name="rj" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">RN</td>
                     <td>Rio Grande do Norte</td>
                     <td>
                        <input type="number" id="input-rn" name="rn" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">RS</td>
                     <td>Rio Grande do Sul</td>
                     <td>
                        <input type="number" id="input-rs" name="rs" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">RO</td>
                     <td>Rondônia</td>
                     <td>
                        <input type="number" id="input-ro" name="ro" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">RR</td>
                     <td>Roraima</td>
                     <td>
                        <input type="number" id="input-rr" name="rr" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">SC</td>
                     <td>Santa Catarina</td>
                     <td>
                        <input type="number" id="input-sc" name="sc" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">SP</td>
                     <td>São Paulo</td>
                     <td>
                        <input type="number" id="input-sp" name="sp" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">SE</td>
                     <td>Sergipe</td>
                     <td>
                        <input type="number" id="input-se" name="se" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
                  <tr>
                     <td class="bold">TO</td>
                     <td>Tocantins</td>
                     <td>
                        <input type="number" id="input-to" name="to" class="form-control" placeholder="Ex: 3">
                     </td>
                  </tr>
               </tbody>
            </table>
         </form>

         <div class="form-btns-bottom">
            <button class="btn btn-success mr-2" type="submit" form="delivery-times-form" id="btn-submit">
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
   <script type="module" src="{{asset('assets/js/pages/prazo-entrega.js')}}"></script>
@endsection
