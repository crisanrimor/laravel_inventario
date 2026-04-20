<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rol\StoreRequest;
use App\Http\Requests\Rol\UpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ver-role', only: ['index']),
            new Middleware('permission:crear-role', only: ['create', 'store']),
            new Middleware('permission:editar-role', only: ['edit', 'update']),
            new Middleware('permission:eliminar-role', only: ['destroy'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);

        return Inertia::render('Roles/Index', [
            'breadcrumb' => [
                ['name' => 'Roles', 'url' => route('dashboard.roles.index')]
            ],
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::all();

        return Inertia::render('Roles/Create', [
            'breadcrumb' => [
                ['name' => 'Roles', 'url' => route('dashboard.roles.index')],
                ['name' => 'Crear Rol', 'url' => route('dashboard.roles.create')]
            ],
            'permisos' => $permisos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try{
            $rol = Role::create([
                'name' => $data['name']
            ]);

            $rol->givePermissionTo($data['permisos']);
            DB::commit();
            return redirect()->route('dashboard.roles.index')->with('success', 'El rol ha sido creado con éxito.');
        }catch(\Throwable $th){
            DB::rollBack();
            return redirect()->route('dashboard.roles.index')->with(['error' => 'Ocurrió un error al crear el rol.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permisos = Permission::all();
        $rol_permisos = $role->permissions->pluck('name')->toArray();

        return Inertia::render('Roles/Edit', [
            'breadcrumb' => [
                ['name' => 'Roles', 'url' => route('dashboard.roles.index')],
                ['name' => 'Crear Rol', 'url' => route('dashboard.roles.create')]
            ],
            'permisos' => $permisos,
            'rol' => $role,
            'rol_permisos' => $rol_permisos
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $data['name']
            ]);

            $role->syncPermissions($data['permisos']);
            DB::commit();
            return redirect()->route('dashboard.roles.index')->with('success', 'El rol ha sido actualizado con éxito.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('dashboard.roles.index')->with(['error' => 'Ocurrió un error al editar el rol.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('dashboard.roles.index')->with('success', 'El rol ha sido eliminado con éxito.');
    }
}
