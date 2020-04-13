{{-- @extends('layouts._main') --}}
{{-- Usando el layout por defecto que genero el scoffolding de auth --}}
@extends('layouts.app')

@section('content')
    @if (Session::has('message'))
        <div class="container alert alert-success">
            {{ Session::get('message') }}
        </div>
    @endif
    
    <div class="container d-flex justify-content-center">
        <h2 class="m-4">All Posts</h2>
    </div>

    <div class="container">
        @foreach ($posts as $post)   
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{{ $post->content }}</p>
                    <div class="d-flex justify-content-end ml-2">
                        {{-- como post.show recibe un parametro se le enviaran en un array como segundo argumentp --}}          
                        <a href="{{ route('posts.show',['post' => $post]) }}" class="btn btn-primary">Read More</a>
                        {{-- Necesitaremos otro boton para editar --}}
                        {{-- @if(Auth::user()->can('update', $post)) --}}
                        @can('update',  $post)
                            <a href="{{ route('posts.edit',['post' => $post]) }}" class="btn btn-primary ml-2">Edit</a>
                        @endcan
                        {{-- Necesitaremos otro boton para eliminar --}}
                        {{-- podemos usar @if(Auth::user()->can('delete', $post)) o la directiva can de blade--}}
                        @can('delete', $post)
                            <form action="{{ route('posts.destroy', ['post' => $post]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger ml-2">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>        
        @endforeach
        {{-- mandaos a llamer al metodo links de nuestro paginator para que aparezca los links de la paginacion --}}
        {{ $posts->links() }}
    </div>
@endsection