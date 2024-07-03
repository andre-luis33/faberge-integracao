<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">


   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <link rel="stylesheet" href=" {{ asset('assets/css/global.css') }} ">
   <link rel="stylesheet" href=" {{ asset('assets/css/alerty.css') }} ">
   <link rel="stylesheet" href=" {{ asset('assets/css/dashboard.css') }} ">

   <title>Faberge Integração</title>
</head>
<body>
   <main>

      @include('admin.partials.sidebar')

      <div class="content">

         <h1 class="main-title">
            @yield('title')
         </h1>

         @yield('content')

      </div>

      <div id="alerty"></div>

   </main>

   <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

   <script type="module" src="{{asset('assets/js/dashboard.js')}}"></script>

   @yield('scripts')
</body>
</html>
