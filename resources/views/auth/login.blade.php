<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | PetCare</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2a5c, #1e5bd8); padding: 16px; }
        .caja { background: #fff; width: 100%; max-width: 400px; border-radius: 16px; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
        .logo { text-align: center; font-size: 28px; font-weight: 700; color: #1e5bd8; }
        .logo small { display: block; font-size: 12px; font-weight: 400; color: #6b7280; margin-top: 2px; }
        h2 { margin: 22px 0 4px; font-size: 20px; }
        .sub { color: #6b7280; font-size: 13px; margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 14px 0 6px; }
        input[type=email], input[type=password] { width: 100%; padding: 11px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        input:focus { outline: 2px solid rgba(30,91,216,.25); border-color: #1e5bd8; }
        .recordar { display: flex; align-items: center; gap: 8px; font-size: 13px; margin: 14px 0; }
        button { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: #1e5bd8; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; }
        button:hover { background: #1749ad; }
        .error { background: #fee2e2; color: #991b1b; padding: 10px 12px; border-radius: 8px; font-size: 13px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="caja">
        <div class="logo">🐾 PetCare <small>Tu mascota, nuestra prioridad</small></div>

        <h2>Iniciar sesión</h2>
        <p class="sub">Accede a tu cuenta para continuar</p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $mensaje)
                    <div>{{ $mensaje }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <label for="email">Correo</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <div class="recordar">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin:0; font-weight:400;">Recordar sesión</label>
            </div>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>