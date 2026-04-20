<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorias por defecto
        $categorias = [
            ['nombre' =>'Gaseosas', 'descripcion' => 'Bebidas gaseosas'],
            ['nombre' =>'Bebidas alcohólicas'],
            ['nombre' =>'Jugos','descripcion' => 'Naturales y artificiales'],
            ['nombre' =>'Bebidas energéticas'],
            ['nombre' =>'Chocolates'],
            ['nombre' =>'Dulces'],
            ['nombre' =>'Papas fritas', 'descripcion' => 'Snacks salados'],
            ['nombre' =>'Galletas'],
            ['nombre' =>'Lacteos'],
            ['nombre' =>'Frutas'],
            ['nombre' =>'Verduras'],
            ['nombre' =>'Carnes'],
            ['nombre' =>'Limpieza', 'descripcion' => 'Productos para aseo del hogar'],
            ['nombre' =>'Aseo personal'],
            ['nombre' =>'Mascotas'],
            ['nombre' =>'Aceite cocina']
        ];

        //Crear categorias
        foreach($categorias as $categoria){
            Categoria::create($categoria);
        }
    }
}
