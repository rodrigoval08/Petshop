<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::FirsOrCreate(
            ['email' =>env('ADMIN_EMAIL', 'admin@petcare.com')],
            [
                'name'     =>env('ADMIN_NAME', 'Administrador'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            ]
        );

        $categorias = [
            ['name' => 'Alimentos',    'description' => 'Comida para perros, gatos y otras mascotas'],
            ['name' => 'Juguetes',     'description' => 'Pelotas, peluches y entretenimiento'],
            ['name' => 'Medicamentos', 'description' => 'Antipulgas, vitaminas y tratamientos'],
            ['name' => 'Higiene',      'description' => 'Shampoo, arena y limpieza'],
            ['name' => 'Accesorios',   'description' => 'Collares, correas y camas'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(['name' => $categoria['name']], $categoria);
        }
    }
}