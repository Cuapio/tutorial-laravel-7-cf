<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
//Para validar los formualrios
use Illuminate\Support\Facades\Validator;//si usamos FormRequest no es necesario solo inyectamos nuestro formRequests
//para validar segun nuestras reglas en FormRequest(ya no usariamos Vcalidator directamente aque)
use App\Http\Requests\UserFormRequest;//esto nos permitira inyectar esta clase en nuestras acciones(metodos)
use Auth;
//Implementamos redis
use Illuminate\Support\Facades\Redis;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return $request->input('name', 'default');//buscamos un parametro y sino existe le podemos mandar un valor por default
        //para poder evaluer el objeto request
        
        //En luagar de llamar a todos los registros, y realentizar el sitio podemos hacer una paginacion
        $posts = Post::paginate(10);//Devuelve objeto del tipo Paginator los primeros 10 de una collection
        // dd($posts); //El objeto paginator contendra la informacion de total de registras, registros por pagina, el numero de paginas(lastpage), pagina actual, items, etc.
        //A nosotros nos interesa items, que contiene los 10 registros de la pagina actual 
        return view('post.index', compact("posts"));//Mostrara solo 10 registros
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');//carpeta resources/views/post
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)//inyectamos nuestro FormRequest
    {
        // dd($request->all());//comprobamos los valores de la peticion para ver lo que se nos envia
        
        //Primero validamos los datos 
        // $validator = Validator::make(request()->all(), [//recibe primero todos los inputs y luego un arreglo con todos los atributos a evaluar
        //     'title' => 'required|min:5|max:10',//en un string definimos todas los reglas de validacion concatenando con el simbolo |
        //     'content' => 'required|min:5|max:50'
        // ]);
        // if($validator->fails()) {//si falla retornara una vista o error a nuestro usuario
        //     return redirect()->route('posts.create')//recargamos la vista con los errores encontrados 
        //         ->withErrors($validator)//devolvemos una variable osea lo que nos devuelva validator
        //         ->withInput();//Este metodo hara que devolvera las entradas que actualmente estan escritas sin tener que llenar todo de nuevo 
        // }

        //como ya tenemos nuestro FormRequest lo usamos


        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        //$post->user_id = 1;//id 1 momentaneo porque no tenemos aun sesiones para asignar automaticamente el id del usuario
        //Ahora que ya implmentaos autenticacion, tenemos disponible el helper user en las peticiones del usuario logueado
        $post->user_id = $request->user()->id;

        $post->save();

        // return view('post.show', compact("post"));//retornamos el post por medio del helper view o con redirect
        return redirect()->route('posts.show', compact("post"))->with('message', 'Post Creado exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $counter = 0;
        //Codigo para redis. Agregamos una variable para determinar las vistas de un post identificandolo por su id. Despues de la primera vista se incrementara de acuerdo a numero de visitas que tenga el post 
        if(Redis::exists('post:views:'.$post->id)) {//Avaluara si la variable existe en redis, usaremos una convencion para nombrar esta, variables. Si existe incrementaremos su valor
            Redis::incr('post:views:'.$post->id);
        } else {
            Redis::set('post:views:'.$post->id, 1);//establecemos nuestra variable en redis.
        }
        $counter = Redis::get('post:views:'.$post->id);

        return view('post.show', compact("post", "counter"));//Recibira la variable post pasada por inyeccion como parametros
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        if(Auth::user()->cant('update', $post)) {//le pasamos el nombre de la accion en la politica que usamos para autorizar
            return redirect()
                    ->route('posts.show', ['post' => $post])
                    ->with('message-cant', 'No tienes permisos para editar este post');
        }
        return view('post.edit', compact("post"));//Recibira la variable post pasada por inyeccion como parametros
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, Post $post)//Usamos igual nuestro form Request
    {
        //
        if(Auth::user()->cant('update', $post)) {//le pasamos el nombre de la accion en la politica que usamos para autorizar
            return redirect()
                    ->route('posts.show', ['post' => $post])
                    ->with('message-cant', 'No tienes permisos para editar este post');
        }

        $post->title = $request->input('title');
        $post->content = $request->input('content');

        $post->save();
        
        return redirect()
                ->route('posts.show', ['post' => $post])
                ->with('message', 'Post Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //Autorizacion mediatne Gate
        // if(Gate::denies('delete-post', $post)) {//Recibe el nombre del gata
        //     return redirect()->back();//simplente nos redireccionara a donde estabaos sin realizar dicha accion que no se tiene acceso, automaticamente el gate recibira el usuario autenticado 
        // }
        //Podemos indicar de manera explicita que usurio esta intentando realizar la accion con forUser()
        // $user = Auth::user();
        // if(Gate::forUser($user)->denies('delete-post', $post)) {
        //     return redirect()->back();
        // }

        //Teniendo Politicas de autorizacion activas podemos realizar la comprobacion de permisos
        if(Auth::user()->cant('delete', $post)) {//le pasamos el nombre de la accion en la politica que usamos para autorizar
            return redirect()
                    ->route('posts.my')
                    ->with('message-cant', 'No tienes permisos para eliminar este post');
        }

        // otra forma de realizar esto es
        // $this->authorize('delete', $post); //Solo que de esta manera te devuelve un error 403 que indica que no estas autorizado
        $id = $post->id;
        $title = $post->title;
        $post->delete();

        return redirect()->route('posts.my')->with('message', "Post Eliminado, {$title} (id {$id})");
    }

    public function myPosts()
    {
        // dd(Auth::user());
        //podemos hacer la siguiente 
        // $posts = Post::all()->where('user_id',Auth::user()->id);//o la forma mas facil es
        $posts = Auth::user()->posts;//esta
        return view('post.my', compact("posts"));
    }
}
