<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuario\StoreRequest;
use App\Http\Requests\Usuario\UpdateRequest;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

use function Illuminate\Support\now;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-user', only: ['index']),
            new Middleware('permission:crear-user', only: ['store']),
            new Middleware('permission:editar-user', only: ['update']),
            new Middleware('permission:eliminar-user', only: ['updateEstado'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();

        return Inertia::render('Usuarios/Index', [
            'breadcrumb' => [
                ['name' => 'Usuarios', 'url' => route('dashboard.usuarios.index')]
            ],
            'usuarios' => $usuarios,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now()
            ]);
            $user->assignRole($data['rol']);
            DB::commit();
            return redirect()->route('dashboard.usuarios.index')->with('success', 'Usuario creado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('dashboard.usuarios.index')->with(['error' => 'Ocurrió un error al crear el usuario.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $usuario)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $usuario->update([
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            $usuario->syncRoles($data['rol']);
            DB::commit();
            return redirect()->route('dashboard.usuarios.index')->with('success', 'Usuario actualizado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('dashboard.usuarios.index')->with(['error' => 'Ocurrió un error al actualizar el usuario.']);
        }
    }

    public function updateEstado(User $usuario)
    {
        $estado = $usuario->estado == 1 ? 0 : 1;
        $message = $usuario->estado == 1 ? 'desactivado' : 'activado';

        $usuario->update([
            'estado' => $estado
        ]);

        return redirect()->route('dashboard.usuarios.index')->with('success', 'Usuario ' .$message .' con éxito.');
    }
}
