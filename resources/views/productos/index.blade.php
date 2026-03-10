@extends('layouts.app')

@php
    use Illuminate\Support\HtmlString;

    // Define BoxIcon once as an HtmlString (so it won’t get escaped)
    $BoxIcon = new HtmlString('
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M9.77692 3.24224C9.91768 3.17186 10.0834 3.17186 10.2241 3.24224L15.3713 5.81573L10.3359 8.33331C10.1248 8.43888 9.87626 8.43888 9.66512 8.33331L4.6298 5.81573L9.77692 3.24224ZM3.70264 7.0292V13.4124C3.70264 13.6018 3.80964 13.775 3.97903 13.8597L9.25016 16.4952L9.25016 9.7837C9.16327 9.75296 9.07782 9.71671 8.99432 9.67496L3.70264 7.0292ZM10.7502 16.4955V9.78396C10.8373 9.75316 10.923 9.71683 11.0067 9.67496L16.2984 7.0292V13.4124C16.2984 13.6018 16.1914 13.775 16.022 13.8597L10.7502 16.4955ZM9.41463 17.4831L9.10612 18.1002C9.66916 18.3817 10.3319 18.3817 10.8949 18.1002L16.6928 15.2013C17.3704 14.8625 17.7984 14.17 17.7984 13.4124V6.58831C17.7984 5.83076 17.3704 5.13823 16.6928 4.79945L10.8949 1.90059C10.3319 1.61908 9.66916 1.61907 9.10612 1.90059L9.44152 2.57141L9.10612 1.90059L3.30823 4.79945C2.63065 5.13823 2.20264 5.83076 2.20264 6.58831V13.4124C2.20264 14.17 2.63065 14.8625 3.30823 15.2013L9.10612 18.1002L9.41463 17.4831Z"
                fill="currentColor"
            />
        </svg>
    ');
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Productos" />
    
    @include('partials.message')
       
    <x-common.component-card title="Nuevo Producto">
        <div class="flex items-center gap-5">
            <a href="{{route('productos.create')}}"><x-ui.button size="sm" variant="primary" :startIcon="$BoxIcon">Crear Producto</x-ui.button></a>
        </div>
    </x-common.component-card>

    <div class="mt-5">
        <x-common.component-card title="Lista de Productos">
            <div class="max-w-full overflow-x-auto">
                <table class="w-full mb-5">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nombre</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Imagen</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Categoria</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Código</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Precio</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Costo</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Stock</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{$producto->nombre}}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="block mr-3 overflow-hidden rounded-full h-11 w-11">
                                        <img src="{{asset('storage/'.$producto->img_path)}}" alt="{{$producto->nombre}}">
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{$producto->categoria->nombre}}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{$producto->sku}}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">${{ number_format($producto->precio, 2, ',', '.') }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">${{ number_format($producto->costo, 2, ',', '.') }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{$producto->stock_actual}}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex gap-2.5">
                                        <a href="{{route('productos.edit', $producto)}}">
                                            <svg class="text-gray-700 cursor-pointer size-5 hover:text-warning-500 dark:text-gray-400 dark:hover:text-warning-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>

                                        <button @click="openModal({{$producto->id}})">
                                            <svg class="text-gray-700 cursor-pointer size-5 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-500" 
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{$productos->links()}}
            </div>
        </x-common.component-card>
    </div>

    <div class="fixed inset-0 items-center justify-center hidden p-5 overflow-y-auto modal z-99999" id="eventModal">
        <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div class="modal-dialog relative flex w-full max-w-[400px] flex-col overflow-y-auto rounded-3xl bg-white p-6 lg:p-11 dark:bg-gray-900">

            <!-- Close Button -->
            <button class="modal-close-btn transition-color absolute top-5 right-5 z-999 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 sm:h-11 sm:w-11 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" fill="" />
                </svg>
            </button>

            <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl lg:text-2xl dark:text-white/90" id="eventModalLabel">
                        Eliminar Producto
                    </h5>
                </div>

                <!-- Modal Body -->
                <div class="mt-8 modal-body">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ¿Estás seguro que quieres eliminar el producto?
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <button type="button" class="modal-close-btn flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Close
                    </button>
                    <form action="" method="POST" id="form-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-add-event bg-red-500 hover:bg-red-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                            Eliminar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        const openModal = (id) => {
            const modal = document.getElementById("eventModal");
            if (modal) {
                modal.style.display = "flex";
                document.body.style.overflow = "hidden";
            }
            const formModal = document.getElementById('form-eliminar');
            formModal.action = `{{url('/productos')}}/${id}`
        };

        const closeModal = () => {
            const modal = document.getElementById("eventModal");
            if (modal) {
                modal.style.display = "none";
                document.body.style.overflow = ""; 
            }
        };

        document.querySelectorAll(".modal-close-btn").forEach((btn) => {
            btn.addEventListener("click", closeModal);
        });
    </script>
@endpush
