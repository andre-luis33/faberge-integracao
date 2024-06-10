<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <title>Document</title>
</head>
<body>

    <div class="login-card">

        <h1 class="title">
            <span>Log</span>in
        </h1>

        <form class="form-group" method="POST" action="{{ route('api.login') }}">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger text-center">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" name="cnpj" class="form-control" placeholder="CNPJ" value="{{ old('cnpj') }}">
            </div>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Senha">
            </div>

            <button class="btn btn-purple">Entrar</button>
        </form>

    </div>

</body>
</html>
{{-- 02755413000101 --}}
