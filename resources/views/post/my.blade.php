@extends('layouts.app')

@section('content')
    @if (Session::has('message-cant'))
        <div class="container alert alert-warning">
            {{ Session::get('message-cant') }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="container alert alert-success">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="container d-flex justify-content-center">
        <h2 class="m-4">My Posts</h2>
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
                    <a href="{{ route('posts.edit',['post' => $post]) }}" class="btn btn-primary ml-2">Edit</a>
                    <form action="{{ route('posts.destroy', ['post' => $post])  }}" method="POST">
                        {{-- por defecto el navegador no entiende peticiones del tipo delete por lo que usamos nuestra directiva @method --}}
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger ml-2">Delete</button>
                    </form>
                </div>
            </div>
        </div>        
        @endforeach

        {{-- {{ $posts->links() }} --}}
    </div>
@endsection