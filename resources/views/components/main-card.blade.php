<div class="main-card">
   <a href="{{route('admin.inicio')}}" class="btn-rounded">
      <i class="fas fa-times"></i>
   </a>

   <div id="main-card-loader" class="loader">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
   </div>

   <div class="main-wrapper">
      @if (isset($slot))
         {!! $slot !!}
      @endif
   </div>
</div>
