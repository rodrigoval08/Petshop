<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Customer;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // RF09: las 4 tarjetas
        $totalProductos = Producto::count();
        $totalClientes  = Customer::count();
        $totalVentas    = Venta::count();
        $montoVentas    = Venta::sum('total');
        $stockCritico   = Producto::critico()->count(); // stock <= 5

        // RF10: productos por agotarse (los de menor stock primero)
        $alertas = Producto::with('categoria')
            ->critico()
            ->orderBy('stock')
            ->orderBy('name')
            ->take(10)
            ->get();

        $ultimasVentas = Venta::withCount('detalles')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProductos',
            'totalClientes',
            'totalVentas',
            'montoVentas',
            'stockCritico',
            'alertas',
            'ultimasVentas'
        ));
    }
}