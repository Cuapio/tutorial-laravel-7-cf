@extends('welcome')
<!-- Aqui se va a sxustituir por un strign por eso utiliza esta sintaxis con dos parametros sipleetne -->
@section('title', "Vista titulo") 

@section('content')
    <p>This is my body content.</p>
@endsection

@section('sidebar')
    <!-- parent agrega la parte de la seccion de la vista padre -->
    @parent     
    <h3>View sidebar</h3>
@endsection 