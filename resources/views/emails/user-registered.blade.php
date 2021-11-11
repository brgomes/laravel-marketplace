<h1>Olá, {{ $user->name }}, tudo bem? Espero que sim!</h1>

<h3>Obrigado por sua inscrição!</h3>

<p>
    Faça bom proveito e excelentes compras em nosso Marketplace.<br><br>
    Seu e-mail de cadastro é: <strong>{{ $user->email }}</strong>.<br><br>
    Sua senha: <em>Por questões de segurança não enviamos sua senha mas você deve se lembrar.</em>
</p>

<hr>
E-mail enviado em {{ date('d/m/Y H:i:s') }}
