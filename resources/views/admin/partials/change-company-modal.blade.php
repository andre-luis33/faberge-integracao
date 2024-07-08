<x-modal id="change-company-modal" title="Trocar Empresa" size="md">
   <x-slot name="body">
      <form id="change-company-form">
         <div class="input-wrapper">
            <i class="fas fa-building"></i>
            <select class="form-control" type="text" id="select-current-company" placeholder="Nome" data-required>
               @foreach (session()->get('user.companies') as $company)
                  <option value="{{ $company['id'] }}" {{ $company['id'] === session()->get('user.company.id') ? 'selected' : '' }}>{{ $company['name'] }}</option>
               @endforeach
            </select>
         </div>
      </form>

   </x-slot>

   <x-slot name="footer">
      <button class="btn btn-secondary">
         Cancelar
      </button>
      <button class="btn btn-purple" type="submit" form="change-company-form" id="change-company-btn-submit">
         Confirmar
      </button>
   </x-slot>
</x-modal>
