<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class TestController extends Controller//traera la logica de nuestra app
{
    ///Aqui pones la logica de tu progrma, como puede ser la de CRUD de nuestro post

    //Ejmplo 1
    public function index() {//Me devolvera todos los post
        $posts = Post::all();
        return view('post.index', compact("posts"));//pued ser un resultado o una vista, em este caso una vista y le pasamos estas variables 
    }
}
