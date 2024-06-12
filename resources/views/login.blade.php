<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
   <title>Faberge Integração - Login</title>
</head>
<body>

   <div class="login-card">

        <h1 class="title">
            <span>Log</span>in
        </h1>

        <form class="form-group">
            <div class="input-wrapper">
               <i class="fas fa-user"></i>
               <input type="text" name="email" class="form-control" placeholder="E-mail" id="email" data-required>
            </div>
            <div class="input-wrapper">
               <i class="fas fa-lock"></i>
               <input type="password" name="password" class="form-control" placeholder="Senha" id="password" data-required>
            </div>

            <button id="btn-submit" type="submit" class="btn btn-purple">Entrar</button>
        </form>

   </div>

   <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
   <script type="module" src="{{ asset('assets/js/login.js') }}"></script>
</body>
</html>
