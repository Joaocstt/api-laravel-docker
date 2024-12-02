<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ativação de Conta</title>
</head>
<body>
<h1>Olá {{ $name }},</h1>
<p>Obrigado por se cadastrar em nosso sistema!</p>
<p>Para ativar sua conta, clique no link abaixo:</p>
<a href="{{ $activationUrl }}">Ativar Conta</a>
</body>
</html>
