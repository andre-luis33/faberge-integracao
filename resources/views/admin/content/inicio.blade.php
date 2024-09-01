@extends('admin.dashboard')
@section('title', 'Início')

@section('content')
   <div class="home-wrapper">
      <i class="fas fa-home"></i>
      <h1>
         Bem vindo!
      </h1>

      <div class="text">
         <p class="mb-0">Comece abaixo</p>
         <i class="fas fa-angle-down"></i>
      </div>

      <div>
         @if(!session('user.admin'))
            <a href="{{route('admin.prazo-entrega')}}" class="btn btn-purple">
               <i class="fas fa-clock"></i>
               Prazo Entrega
            </a>
            <a href="{{route('admin.grupos-de-peca')}}" class="btn btn-purple">
               <i class="fas fa-cog"></i>
               Grupo de Peças
            </a>
            <a href="{{route('admin.integracao')}}" class="btn btn-purple">
               <i class="fas fa-project-diagram"></i>
               Integração
            </a>
         @else
            <a href="{{route('admin.usuarios')}}" class="btn btn-purple">
               <i class="fas fa-users"></i>
               Usuários
            </a>
         @endif
      </div>
   </div>
@endsection
