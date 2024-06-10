<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href=" {{ asset('assets/css/global.css') }} ">
    <link rel="stylesheet" href=" {{ asset('assets/css/dashboard.css') }} ">

    <title>Document</title>
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

   </main>
</body>
</html>
