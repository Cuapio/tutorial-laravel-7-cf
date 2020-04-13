@extends('layouts.app')

@section('content')
    {{-- Comprobar si existe algun mensaje, para ello, usamos el facade Session. Message es el nombre de la variable que vamos a buscar --}}
    @if (Session::has('message'))
        <div class="container alert alert-success">
            {{ Session::get('message') }}
        </div>
    @endif
    @if (Session::has('message-cant'))
        <div class="container alert alert-warning">
            {{ Session::get('message-cant') }}
        </div>
    @endif
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <p>views {{ $counter }}</p>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"> {{ $post->title }} </h5>
                        <p class="card-text"> {{ $post->content }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection