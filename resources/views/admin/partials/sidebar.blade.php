<aside class="navbar">
   <header>
      <img alt="Logo Grupo Faberge" src="{{ asset('assets/img/logo-faberge.png') }}">
   </header>

   <nav>
      <ul class="menu">
         <li>
            <a class="{{ Request::is('admin/inicio') ? 'active' : ''}}" href="{{route('admin.inicio')}}">
               <i class="fas fa-home"></i>
               <span>Início</span>
            </a>
         </li>
         <li>
            <a class="{{ Request::is('admin/prazo-entrega') ? 'active' : ''}}" href="{{route('admin.prazo-entrega')}}">
               <i class="fas fa-clock"></i>
               <span>Prazo Entrega</span>
            </a>
         </li>
         <li>
            <a class="{{ Request::is('admin/grupos-de-peca') ? 'active' : ''}}" href="{{route('admin.grupos-de-peca')}}">
               <i class="fas fa-cog"></i>
               <span>Grupo de Peças</span>
            </a>
         </li>
         <li>
            <a class="{{ Request::is('admin/parametro') ? 'active' : ''}}" href="{{route('admin.parametro')}}">
               <i class="fas fa-sliders-h"></i>
               <span>Parâmetro</span>
            </a>
         </li>
      </ul>

      <a href="" class="logout-link">
         <i class="fas fa-sign-out-alt"></i>
         <span>Sair</span>
      </a>
   </nav>

   <button onclick="document.querySelector('.navbar').classList.toggle('closed')" class="btn-rounded">
      <i class="fas fa-angle-left"></i>
   </button>
</aside>
