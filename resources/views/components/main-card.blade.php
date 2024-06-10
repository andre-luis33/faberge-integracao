<div class="main-card">
   <a href="{{route('admin.inicio')}}" class="btn-rounded">
      <i class="fas fa-times"></i>
   </a>

   @if (isset($slot))
      {!! $slot !!}
   @endif
</div>
