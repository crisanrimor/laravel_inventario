<?php

namespace App\Http\Controllers;

use App\Helpers\CloudinaryHelper;
use App\Http\Requests\Perfil\UpdateImageRequest;
use App\Http\Requests\Perfil\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(){
        return Inertia::render('Perfil/Edit', [
            'breadcrumb' => [
                ['name' => 'Editar Perfil', 'url' => route('dashboard.perfil')]
            ]
        ]);
    }

    public function changePassword(UpdatePasswordRequest $request){
        $data = $request->validated();

        $usuario = Auth::user();

        $usuario->update([
            'password' => Hash::make($data['password'])
        ]);

        return redirect()->route('dashboard.perfil')->with('success', 'La contraseña ha sido actualizada con éxito.');
    }

    public function changeImage(UpdateImageRequest $request){
        $usuario = $request->user();

        $path = CloudinaryHelper::upload(
            $request->file('image')->getRealPath(),
            'inventario/usuarios'
        );

        if($usuario->image){
            CloudinaryHelper::delete($usuario->image);
        }

        $usuario->update([
            'image' => $path
        ]);

        return redirect()->route('dashboard.perfil')->with('success', 'Imagen actualizada con éxito.');
    }
}
