<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de senha</title>
</head>
<body>
<h1>Olá, {{ $user->name }}</h1>
<p>Você solicitou a redefinição de senha. Por favor, clique no link abaixo para redefinir sua senha:</p>
<a href="{{ route('password.reset', ['token' => $token]) }}">Redefinir Senha</a>
<p>Caso não tenha solicitado a redefinição de senha, por favor, ignore este e-mail.</p>
</body>
</html>
