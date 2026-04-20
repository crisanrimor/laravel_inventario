<?php

namespace App\Http\Controllers;

use App\Http\Requests\Perfil\UpdateImageRequest;
use App\Http\Requests\Perfil\UpdatePasswordRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $path = $request->file('image')->store('usuarios', 'public');

        if($usuario->image && Storage::disk('public')->exists($usuario->image)){
            Storage::disk('public')->delete($usuario->image);
        }

        $usuario->update([
            'image' => $path
        ]);

        return redirect()->route('dashboard.perfil')->with('success', 'Imagen actualizada con éxito.');
    }
}
