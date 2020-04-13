@extends('layouts.app')

@section('content')

    @if ($errors->any()) {{-- variable que siempre se pasa en las vistas, evaluamos si ontiene algun error para ello usamos any --}}
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>                 
    @endif

    {{-- le pasamos el nombre de la accion y el metodo correspondiente. En este caso como creamos un post la ruta store recibe peticiones de tipo POST --}}
    <form action="{{ route('posts.store') }}" method="POST">
    {{-- Todas las peticiones que se enviem, tienen que enviar un token para que aravel sepa que la peticion qu estamos enviando es de nuestra misma aplicacion. Esto es para evitar ataques XSS para ello laravel ofrece una directiva csrf --}}
        @csrf 
        <div class="row justify-content-center">
            <div class="col-sm-7">
                <div class="form-group">
                    <label for="title">Titulo</label>
                    <input 
                        type="text"
                        class="form-control" 
                        name="title" id="title" 
                        placeholder="Titulo" 
                        value="{{ old('title') }}" {{-- Este campo es para que no se elimine el valor cuando se haga la validacion y un campo este incorrecto, osea se mantiene mediatne lo que noos devuelve el helper old() --}}
                    >
                </div>
                <div class="form-group">
                    <label for="content">Titulo</label>
                    <textarea 
                        class="form-control" 
                        name="content" 
                        cols="30" 
                        rows="10"
                    >{{ old('content') }}{{-- Este campo es para que no se elimine el valor cuando se haga la validacion y un campo este incorrecto, osea se mantiene --}}</textarea>
                </div>
            </div> 

            <div class="col-sm-7 text-center">
                <button class="btn btn-primary btn-blok">
                    Create
                </button>
            </div>
        </div>
    </form>
@endsection