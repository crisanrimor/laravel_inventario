@if(session('success'))
    <div class="mb-5">
        <x-ui.alert
            variant="success"
            title="Operación exitosa"
            message="{{session('success')}}"
            :showLink="false"
        />
    </div> 
@endif

@if(session('warning'))
    <div class="mb-5">
        <x-ui.alert
            variant="warning"
            title="Advertencia"
            message="{{session('warning')}}"
            :showLink="false"
        />
    </div> 
@endif

@if ($errors->any())
    <div class="mb-5">
        <x-ui.alert
            variant="error"
            title="Error"
            message="{{ $errors->first('error') }}"
            :showLink="false"
        />
    </div> 
@endif