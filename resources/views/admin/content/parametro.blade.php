@extends('admin.dashboard')
@section('title', 'Parâmetro')

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

      <h2 class="side-title">
         Período de Atualização
      </h2>

      <div class="pl-4 mt-3">
         <p class="text-purple-primary">
            Selecione abaixo uma opção.
         </p>

         @foreach($executionsIntervals as $interval)
            <button class="btn {{ $interval === $selectedInterval ? 'btn-purple' : 'btn-outline-purple' }}">
               {{$interval}} min
            </button>
         @endforeach

      </div>


   </x-main-card>

@endsection
