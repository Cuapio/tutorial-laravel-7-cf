<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
</head>
<body>
    <!-- Sintaxis de blade, nos permite agregar de manera mas facil codigo en html, que posteriorente compilara a php, en la carpeta storage/framework/views -->
    @if(true)
    <h1>Esta es la view, {{ $name }} {{ $lastname }}</h1>
    @endif

    @foreach($array as $value) 
    <p> {{ $value }} </p>
    @endforeach

    @php
    function hi() {
        return "hi!";
    } 
    @endphp
    <p>{{ hi() }}</p>
</body>
</html>