@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Crear Compra" />

    <div class="grid grid-cols-1">
        <div class="space-y-6 w-2xl mx-auto">
            <x-common.component-card title="Formulario Crear Compra">
                <form action="{{route('compras.store')}}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="persona_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Proveedor:
                        </label>

                        <select name="persona_id" id="persona_id" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" disabled selected>
                                -- Selecciona un proveedor --
                            </option>

                            @foreach($personas as $persona)
                                <option value="{{ $persona->id }}">
                                    {{ $persona->nombre }}
                                </option>
                            @endforeach 
                        </select>

                        @error('persona_id')
                            <p class="text-theme-xs text-error-500 mt-1.5">
                                {{$message}}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.button size="sm" variant="outline" type="submit">Crear</x-ui.button>
                    </div>
                </form>            
            </x-common.component-card>
        </div>
    </div>
@endsection
|