<aside class="sidebar {{ session()->get('user.sidebar.closed') ? 'closed' : '' }}">
   <header>
      <img alt="Logo Grupo Faberge" src="{{ asset('assets/img/logo-faberge.png') }}">
   </header>

   <button class="btn-change-company" data-toggle="modal" data-target="#change-company-modal">
      <i class="fas fa-building left-icon"></i>

      <div class="text-wrapper">
         <span>
            {{ session()->get('user.company.name') }}
         </span>
         <span>
            <i class="fas fa-sync-alt"></i>
            Trocar
         </span>
      </div>
   </button>

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
            <a class="{{ Request::is('admin/empresas') ? 'active' : ''}}" href="{{route('admin.empresas')}}">
               <i class="far fa-building"></i>
               <span>Empresas</span>
            </a>
         </li>
         <li>
            <a class="{{ Request::is('admin/integracao') ? 'active' : ''}}" href="{{route('admin.integracao')}}">
               <i class="fas fa-project-diagram"></i>
               <span>Integração</span>
            </a>
         </li>
      </ul>

      <a href="#" class="logout-link" id="btn-logout">
         <i class="fas fa-sign-out-alt"></i>
         <span>Sair</span>
      </a>
   </nav>

   <button id="sidebar-btn" class="btn-rounded">
      <i class="fas fa-angle-left"></i>
   </button>
</aside>
