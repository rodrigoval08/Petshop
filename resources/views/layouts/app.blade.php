<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') | PetCare</title>
    <style>
        :root {
            --azul: #1e5bd8; --azul-oscuro: #0f2a5c; --fondo: #f3f6fb;
            --texto: #1f2937; --gris: #6b7280; --borde: #e5e7eb;
            --rojo: #dc2626; --naranja: #f59e0b; --verde: #16a34a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: var(--fondo); color: var(--texto); }
        a { text-decoration: none; color: inherit; }

        /* Estructura */
        .app { display: flex; min-height: 100vh; }
        .sidebar { width: 230px; background: var(--azul-oscuro); color: #fff; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 20; transition: transform .25s; }
        .marca { padding: 20px 18px; font-size: 20px; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,.1); }
        .marca small { display: block; font-size: 11px; font-weight: 400; opacity: .7; margin-top: 2px; }
        .sidebar nav { flex: 1; padding: 12px 8px; overflow-y: auto; }
        .sidebar nav a { display: block; padding: 10px 14px; border-radius: 8px; margin-bottom: 4px; font-size: 14px; color: #cbd5e1; }
        .sidebar nav a:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar nav a.activo { background: var(--azul); color: #fff; font-weight: 600; }
        .sidebar .usuario { padding: 14px 18px; border-top: 1px solid rgba(255,255,255,.1); font-size: 13px; }
        .sidebar .usuario button { margin-top: 8px; width: 100%; padding: 8px; border: 0; border-radius: 6px; background: rgba(255,255,255,.12); color: #fff; cursor: pointer; }
        .sidebar .usuario button:hover { background: var(--rojo); }
        .principal { flex: 1; margin-left: 230px; min-width: 0; }
        .topbar { background: #fff; padding: 14px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--borde); position: sticky; top: 0; z-index: 10; }
        .topbar h1 { font-size: 20px; flex: 1; }
        .topbar .quien { font-size: 13px; color: var(--gris); }
        .menu-btn { display: none; background: none; border: 0; font-size: 24px; cursor: pointer; }
        .contenido { padding: 24px; }

        /* Alertas flash */
        .alerta { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alerta-exito { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alerta-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alerta ul { margin-left: 18px; }

        /* Paneles y tarjetas */
        .panel { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.06); margin-bottom: 20px; }
        .panel-titulo { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
        .panel-titulo h2 { font-size: 17px; }
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .tarjeta { background: #fff; border-radius: 12px; padding: 18px; box-shadow: 0 1px 3px rgba(0,0,0,.06); border-left: 5px solid var(--azul); }
        .tarjeta .etiqueta { font-size: 13px; color: var(--gris); }
        .tarjeta .valor { font-size: 28px; font-weight: 700; margin-top: 4px; }
        .tarjeta.verde { border-color: var(--verde); }
        .tarjeta.morada { border-color: #7c3aed; }
        .tarjeta.roja { border-color: var(--rojo); }

        /* Tablas */
        .tabla-wrap { overflow-x: auto; }
        table.tabla { width: 100%; border-collapse: collapse; font-size: 14px; }
        .tabla th { text-align: left; padding: 10px 12px; background: #f8fafc; color: var(--gris); font-size: 12px; text-transform: uppercase; border-bottom: 1px solid var(--borde); }
        .tabla td { padding: 12px; border-bottom: 1px solid var(--borde); vertical-align: middle; }
        .tabla tr:hover td { background: #fafcff; }
        .acciones { display: flex; gap: 6px; }
        .vacio { text-align: center; color: var(--gris); padding: 28px; }

        /* Botones */
        .btn { display: inline-block; padding: 9px 16px; border-radius: 8px; border: 0; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primario { background: var(--azul); color: #fff; }
        .btn-primario:hover { background: #1749ad; }
        .btn-secundario { background: #e5e7eb; color: var(--texto); }
        .btn-peligro { background: var(--rojo); color: #fff; }
        .btn-sm { padding: 6px 11px; font-size: 13px; }

        /* Formularios */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .form-grupo label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-grupo input, .form-grupo select, .form-grupo textarea { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; background: #fff; }
        .form-grupo input:focus, .form-grupo select:focus, .form-grupo textarea:focus { outline: 2px solid rgba(30,91,216,.25); border-color: var(--azul); }
        .form-grupo .error { color: var(--rojo); font-size: 12px; margin-top: 4px; }
        .form-pie { margin-top: 20px; display: flex; gap: 10px; }
        .barra-filtros { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
        .barra-filtros input, .barra-filtros select { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
        .barra-filtros input { flex: 1; min-width: 180px; }

        /* Etiquetas de stock (RF10) */
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; color: #fff; }
        .badge-critico { background: var(--rojo); }
        .badge-bajo { background: var(--naranja); }
        .badge-normal { background: var(--verde); }

        /* Paginación */
        .pagination { display: flex; gap: 4px; list-style: none; margin-top: 16px; flex-wrap: wrap; }
        .page-link { display: block; padding: 6px 12px; border: 1px solid var(--borde); border-radius: 6px; background: #fff; font-size: 13px; }
        .page-item.active .page-link { background: var(--azul); color: #fff; border-color: var(--azul); }
        .page-item.disabled .page-link { color: #9ca3af; }

        /* Responsive */
        @media (max-width: 800px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.abierto { transform: translateX(0); }
            .principal { margin-left: 0; }
            .menu-btn { display: block; }
            .contenido { padding: 16px; }
        }
    </style>
    @stack('estilos')
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="marca">🐾 PetCare <small>Tu mascota, nuestra prioridad</small></div>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'activo' : '' }}">🏠 Inicio</a>
            <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.*') ? 'activo' : '' }}">📦 Productos</a>
            <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'activo' : '' }}">🗂️ Categorías</a>
            <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'activo' : '' }}">👥 Clientes</a>
            <a href="{{ route('mascotas.index') }}" class="{{ request()->routeIs('mascotas.*') ? 'activo' : '' }}">🐶 Mascotas</a>
            <a href="{{ route('ventas.create') }}" class="{{ request()->routeIs('ventas.create') ? 'activo' : '' }}">🛒 Nueva venta</a>
            <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.index') ? 'activo' : '' }}">🧾 Historial de ventas</a>
            <a href="{{ route('diagramas.index') }}" class="{{ request()->routeIs('diagramas.*') ? 'activo' : '' }}">📐 Diseño (StarUML)</a>
        </nav>
        <div class="usuario">
            👤 {{ auth()->user()->name }}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <div class="principal">
        <header class="topbar">
            <button class="menu-btn" type="button" onclick="document.getElementById('sidebar').classList.toggle('abierto')">☰</button>
            <h1>@yield('titulo', 'Inicio')</h1>
            <span class="quien">{{ auth()->user()->email }}</span>
        </header>

        <main class="contenido">
            @if (session('exito'))
                <div class="alerta alerta-exito">✅ {{ session('exito') }}</div>
            @endif

            @if (session('error'))
                <div class="alerta alerta-error">⚠️ {{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alerta alerta-error">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul>
                        @foreach ($errors->all() as $mensaje)
                            <li>{{ $mensaje }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('contenido')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>