<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Una ruta es un identificador que responde con un controlador o una accion, que nos devolvera una estructura o un recurso(html, pdf, etc)

//HELPERS
//Los helpers solo son funciones php que vienen por defecto en laravel 

// Para declarar rutas solo usamos la clase Route, y usamos uno de los metodos HTTP(get, delete. entre otros) 
// Route::get('/', function () {//get recibe la ruta y un closure con la logica que vamos a devolver
//     return view('secc');//helper view busca y devuelve la vista 
// });

Route::get("/saluda/{name?}", function($name="Desconocido") {//Tambien podemos usar paraetros haciendo uso de llaves, y lo recibimos en el closure.
    return "Hola, {$name}, esta es la ruta saluda";//El signo de interrogacion despues del nombre del parametro nos permite usar valores por defecto sino se es especificado el parametro en la url
})->where('name', '[A-Za-z]+');//where nos permite definir un patron(regex) valido en los parametros recibidos

Route::group(["prefix" => "admin"], function() {//Nos permite agrupar un conjunto de rutas siguiendo ciertas caracteristicas, o middlewares entre otras cosas. En este caso seran rutas con precedencia de admin en la url, por ejemplo '/admin/saluda/Mario'
    Route::get("/saluda/{name?}", function($name="Jefe") {
        return "Hola, {$name}, esta es la ruta saluda";
    })->name('routeName');//el metodo name nos permite nobrar una ruta, evitando la necesidad de modificar todos los lugares donde se llame a la ruta manualmente si es que cambiamos el nombre la ruta. Podemos hacer uso de esto usando helpers en href de una anchor tag, por ejemplo,
    // href="{{ route('routeName') }}". de esta manera estamos buscando por el nombre de la ruta y no el la ruta en la url
});

Route::any('/foo', function() {//Podemos mapear una ruta que maneje cualquier tipo de metodo http 
    return "ruta con metodo any";
});

Route::match(['put', 'post'], '/match', function() {//Hace lo mismo que any solo que, con los metodos especificados en un arreglo enviado como parametro 
    return "ruta con metodo match de Route";
});

Route::redirect('/red', '/admin/saluda/');//Nos hace un redirect a dicha ruta. Creo usa Nginx

// Algunos helpers comunes son dd que nos ayudara debuguear, al pasarle un objeto y nos devolvera un resultado de tipo de valor, lo cual nos puede ayudar a inspeccionar el tipo de objeto y su respuesta. otros helpers que se ven son view() para llamar a vistas d la carpeta view 

//Las vistas pueden ser devueltas desde controladores o desde las rutas 
Route::get('/view', function() { 
    $name = 'Mario' ;
    $lastname = 'Cuapio';
    $array = [1, 2, 3, 4, 5];
    // return view('view')->with("name", $name);//Algo comun es el enviar variables a nuestras vistas y esto lo hacemos con with
    //Tambien podemos usar otra funcion dentro de view() qe permite pasarle las varibales solo con el nombre
    return view('view', compact("name", "lastname", "array"));
});

Route::view('/vue', "");//

//BASES DE DATOS
//para probar la conexion 
//use Illuminate\Support\Facades\DB;
Route::get('/db', function() {
    try{
        DB::connection()->getPdo();
    } catch(\Exception $err) {
        die("Could not connect to the database. Please check your configuration error:".$err);
    }
});

//MIGRACIONES
//Son archivos que mantinen los cambios efetuados que se hacen a nuestra base de datos en un proyecto, parecido a un control de versiones del schema de tu BD
//Para estos propositos laravel ofrece artisan, una herramienta de linea de comandos, que facilirtara la tarea para la modificacion del esquema de la BD. 
// Para crear una tabla usamos: php artisian make:migration create_name_tale
//para ejecutar la estructura del esquema creado: php artisan migrate
//Para eliminar el esquema creado: pho artisan migrate:rollback
//Para entrar al gestor de bases de datos: mysql -u [DB name] -p
//Usar la BD indicada: use [BDname]
//Para visualizar la etructura de una tabla: DESCRIBE [table name]

//Si queremos modificar estructura de la tabla tenemos tomar en considracion si estamos en produccion o no ya que esto puede significar perdidad de ddatos, por lo que debemos de hacer es modificar la tabla ya existente, de la siguiente manera
// php artisan make:migration add_user_id_to_[tablename]_table. 
// Esto nos generara un archivo en migrations en l cual debemos de indicar los cambios (checa AddUserIdAndPostIdToCommentsTable y add_user_i_to_posts_table) Despues volvemos a correr 'php artisan migrate' para efectuar los cambios

//MODELOS 
//Son una representacion de una tabla en la BD y se crean directamente en la carpeta app, ahi hay un archivo user.php el cual es una representacion de la tabla usuarios. Laravel sabe a que tabla esta relacionada esa clase (User.php), es ahi donde viene el concepto configuracion sobre convencion, esto quiere decir que Laravel automatica mente toa el nombre del modelo(class User) y busca en su contrapartida el plural como la tabla en la base de datos. Por ello se nombran las tablas en plural e ingles
//Para esto utillizaremos tinker el cual es una heramienta de linea de comandos y nos perite interactuar con la aplicacion de laravel. Se manda a llamar con el comando'php artisan tinker'
//Estando dentro mandamos a llaar un modelo como user asi, 'User::all()' esto nos permitira consultar la tabla users pero aun no tenemos regitros por lo que devolvera []
//Para crear un registro usamos 'User::create(["name" => "luis", "email" => "email@gmail.com","password" => "sadfads])'
//Para crear un modelo nuevo utilizaos 'php artisan make:model Post' recuerda que por convencion el odelo es en singular. Depsues podemos hacer el mismo procedimiento con tinker como con User. 
//Es importante especificar que campos podemos asignar en nuestras tablas y eso se hace con la propiedad fillable dentro del modelo. Otra cosa es que si tienes llaves foraneas debera de coincidir con un valor existente en la tabla origen de dicha llave

//SEEDERS
//Laravel provee un mecanismo para llenar nuestra base de datos con informacion ficticia, gracias a los sedeers y se encuentran dentro de database/seeds. Para poder crearlos nos apoyamos de artisan, con el comando 'php artisan ake:seeder UserTableSeeder' como podras observar tambien los seeder los nobramos por convencion de acuerdo a los nombres de nuestras tablas. Para ejecutar nuestros seeders usamos 'php artisan db:seed'
// laravel provee de una libreria faker y los factories para poder insertar registros en las DB solo definiendo una estructura y no manuelente uno x uno. Los factories son archivos donde se especifica una estructura de datos para nuestros modelos y se crean en la carpeta databas/factories y las podemos crear usando el comando 'php artisan make:factory [PostFactory]' definiendo el nombre en convencion con el nombre de la tabla en singular. faker es una libreria que nos permitira generar informacion ficticia(checar los archivos seeders de database/seeds). Despues de desarrollarlos ejecutamos el comando 'php artisan db:seed' para que se generen los registros.
// Para agregar un nuevo seeder para llamar a nuestras factories usamos 'php artisan make:seeder [PostsTableSeeder]' usando la convencion para el nombre de nuevo.

//ELOQENT(ORM)
//Nos ofrece una gran cantidad de metodos para poder interectuar con nuestras tablas, podemos acceder a la consola para eloquente con el comando 'php artisan tinker'. Estos metodos generalmetne nos estara devolviendo la colleccion de objetos consultados
// Hasta ahora diremos que el metodo User::all() nos devuelve, en realidad intancias de la clase user haciendo un mapeo de un registro especifico con las propiedades de una instancia de User. De hecho all() es un metodo que nos ofrece Eloquent abraer las consultas de una manera mas sencillas sin tener que realizar senteencias sql directamente contra la base de datos permitiendonos .
//Otro metodo es find() que recibe como parametro el id de cierto registro de una tabla especifica por ejemplo User::find(1), tambien esta findOrFail() que hace lo mismo solo que si no lo encuetra nos estaria devolviendo un error 404
//User::where('id', '=', 1)->get() Nos devolvera un objeto de tipo builder con la condicion senalada y pora obtener la forma de un objeto de su instancia User lo concatenamos con ->get()
//Tambien en una sesion de tinker podemos declarar una variable con el valor de una istancia de modelo(User) para despues editarlo en la base de datos, asi: 
// $user = User::find(1)
// $user->name = 'Mario Cuapio' //Esto no sera suficiento solo se modifico dentro de la instancia
// $user->save()//Para poder efectuar los cambios en la base debemos de usar el metodo save()
//Para contar registros User::count()
// Para eliminar $user = User::find(40) despues $user->delete()
//Para colocar condiciones User::all()->where('id', '<=', 6)
//Ya que la mayoria de los metodos devuelven instancia de collectiones podemos concatenar los metodos que querramos y esten disponibles en las colleciones, por ejemplo al anterior ejemplo podemos concatenar ->count() para que nos devuelva la cantida de registros que cumplen esa condicion de id<=6 
/* While most Eloquent collection methods return a new instance of an Eloquent collection(https://laravel.com/docs/7.x/eloquent-collections#available-methods), the pluck, keys, zip, collapse, flatten and flip methods return a base collection instance.(https://laravel.com/docs/7.x/collections#available-methods) */

//Ejemplo con Eloquent de un endpoint 
Route::get('/users', function() {
    // probamos, las relaciones que definimos en app/User.php con el metodo postsx
    // dd(App\User::with(['posts'])->get());//dd nos devolvera el objeto desplegado(tipo builder) que devuelve la sentencia como parametro. Con with obtenemos las objetos builder que devuelve Eloquent, pero si concatenamos el metodo ->get() nos devolvera la coleccion de datos que contiene el array de intancias que genero de los registros en la base, del tipo User en el habra un atributo relationships que contendra a su vez una collection con todos los post de dicho usuario, tambien en llugar de get podemos usar first para solo obtener un registro
    
    //dd(App\User::with(['posts'])->first()->posts()->first()->id);//aqui obteneos al primer insstancia usuario, con su metodo posts obtenemos una collection de posts, despues, solo tomamos el primero e imprimimos su id'

    // dd(User::with(['posts'])->first()->posts()->get()) //obtenemos todos los posts del primer intancia user

    // //aqui llamamos a ls usuarios con relaciones en posts pero con una condicion(where)
    // $user = App\User::with(['posts'])->where('id', 1)->get();//get nos devuelve la collection
    // dd($user);

    //aqui usamos usamos una funcion para condicionar las relaciones con posts, recibira iuna consulta como parametro 
    $user = App\User::with(['posts' => function($query) {//esta funcion restringe bajo ciertos parametros a los posts
        //Podemos tener multiples restriccines para nuestros posts
        $query->Where('id', 1);//query representa un objeto del tippo Eloquent. En este caso, aqui, condicionamos al post con id 1
        // $query->Where();
    }])->where('id', 1)->get();//get nos devuelve la collection, aqui condicionamos al usuario con id 1
    dd($user);
});

//Relaciones entre modelos
//Aqui Elequent ofrece metodos para determinar la cardinalidad de las relaciones entre las entidades(tablas) en nuestra Db, como puede ser, 1 a 1, 1 a muchos, muchos a muchos, etc.(Mas info. en https://laravel.com/docs/7.x/eloquent-relationships#defining-relationships) Anteriormente ya definimos una relacion en nuestro modelo User con el metodo posts y en el retornabamos los posts relacionados con dicho Usuario(en forma de intancia User) con el metodo que ofrece Eloquent de return '$this->hasMany('App\Models\Post')'
// Otros metodos disponibles son 

//QUERY BUILDER
//A veces puede que Eloquent se torne un poco incomodo o simplemente nuestra query sea demasiado extensa o simplemente hay veces que Eloquent no puede realizar una peticion, como por ejemplo, llamar a un procedimiento almacenado. Por ello tenemos otra forma de realizar querys y es a traves de query Builder. es una interfaz para ejecutar querys a nuestra DB.
//Primero debemos de importar al facade(fachada)
use Illuminate\Support\Facades\DB;
Route::get('/query', function() {
    //Ejemplo 1. obtene todos los registros de mi tabla users
    // $users = DB::table('users');//Devuelve un objeto builder
    // dd($users->get());//get para que nos devuelva las collecciones de nuestro registros. Nota que al estar devolviendonos collecciones podremos acceder a todos los metods disonibles de Collection(https://laravel.com/docs/7.x/collections#available-methods)

    // Ejemplo 2
    // $users = DB::table('users')->where('email', 'raul.connelly@example.org')->first();
    // dd($users);//Otra diferencia es que nos devolvera un arreglo a diferencia con Eloquent que nos devolvia una intancia del Modelo, en ete caso User

    //Ejemplo 3
    // $users = DB::table('users')
    //     ->join('posts', 'users.id', 'posts.user_id')
    //     //->join() //Podemos utilizar los joins que queramos 
    //     ->get();//le especificamos la tabla a relacionar y los campos que comparten antecediendo el nobre de la tabla antes del campo relacionado 
    // dd($users);
    
    //Ejemplo 4 NOTA que los joins en Eloquente se acen con with
    $users = DB::table('users')
        ->join('posts', 'users.id', 'posts.user_id')
        ->select('users.id', 'users.name', 'posts.title', 'posts.content')//aqui hacemos una seleccion de campos especificos
        ->get();//le especificamos la tabla a relacionar y los campos que comparten antecediendo el nobre de la tabla antes del campo relacionado 
    dd($users);
});

//CONTROLADORES
//Anteriormente hicimos
// Route::get('/', function () {
//     $name = 'assfas'
//     return view('welcome');
// });
//Enviavamos una variable directamente a nuestra vista, si bien no esta mal, lo ideal es usar controladores en estos casos. 
//los controladores son clases las cuales contienen acciones o metodos que son los encargados de tener la logica de una peticion
//Hasta este momento no habiamos usados las vistas para interactuar con nuestra base de datos, aqui es donde los controladores, tambien nos ayudaran a comunicar a las vistas con los modelos y viceversa
//Para crear controladores tambien utilizamos artisan: 'php artisan make:controller [NameController]' lo cual creara un nuevo archivo en app/Http/Controllers 
//Ahora que definimos nuestra logica en el controlador podemos hacer lo siguiente 
//Route::get('/testcontroller', 'TestController@index');//en vez de pasarle un closure, pasamos un string con el nombre del controlador + @ + el nombre la accion

//otra Posibilidad es realizar la logica de un crud con una api rest, sin embargo hacerlo manualmente puede ser un tanta complejo por lo que laravel nos pone a disposicion los controladores Rest. Son clases, igulmente del tipo Controller ero con una definicion de todos los metodos del crud. Para ello, utilizamos 'php artisan make:controller [NameController] --resource' que es lo mismo pero con un flag resource
// Route::resource('/poststest', 'PostControllerTest');//Con resource() la ruta indicaa(/posts) va a definir la forma de acceder a nuestros recursos desde la url, en el segundo parametro s ya no es necesario usar @ depsues del nombre del controlador por defecto resource ya maneja las acciones para cada url o ruta del crud
//Para saber el nombre de todas las rutas que hemos definido podemos utilizar 'php artisan route:list', el cual arrojara todas las rutas para modificar un recurso post desde metodos GET, POST, PUT, DELETE, etc. y describe tambien el action por defecto o en su defecto el closure que le pasaste a cierta ruta. En este caso en el controlador se definira todas las acciones del CRUD para los posts, que son index, create, store, edit, update, show y destroy. Los cuales tendran una ruta asociciado, por ejemplo, la ruta posts/create corresponde la acion PostController@create. por defecto resource le define un nombre a la ruta por si, en dado caso, cambiamos la ruta en un futuro, en este caso el nombre que se le asigna es posts.create, esta es una referencia a esta ruta y asi podemos acceder a esta ruta desde otras partes del programa.
//otra MEJOR forma para CREAR nuestros CONTROLADORES en asi 'php artisan make:controller [NameController] --resource --model=Models/Post' donde especificamos una nueva flag con el modelo que estara manejando el controlador, de esta forma laravel hace inyeccion de dependencias de nuestro Modelo en el Controlador, asi podemos estar usando instancias de la clase del modelo de manera mas facil ademas de que tambien inyecta la logica de las clases Request y REsponse que ponen a nuestra disposicion cierta logica para atender peticiones y devolver respuestas al usuario
//Si necesitas ciertos metodos del controlador podemos definir los metodos disponibles para dicha ruta desde este archivo asi:
// Route::resource('/posts', 'PostController')->only([//definimos los metodos que queremos utilizar por lo cual seran aceptados
//     'index', 'create'
//]); //o  bien podemos usar ->except()
// Route::resource('/posts', 'PostController')->except('create');//definimos los metodos que queremos deshabilitar por lo cual seran rechazados
Route::resource('/posts', 'PostController');

//MIDDLEWARES
//Son una especie de metodos para filtrar peticiones http, osea cada peticion del cliente tendra que ser evaluada por cada una de los middlewares antes de poder utilizar la informacion. Para definir un middlewares nos aoyamos de artisa con el siguiente comando 'php artisan make:middleware [NameOfMiddleware]' el cual creara la estructura en un archivo en la carpeta app/http/Middleware 
//Para hacer uso de nuestro middleware Language podemos hacer lo siguiente
Route::get('/welcome', function() {
    return view('secc');
})->middleware('language');//indicaos el Mw que utilizaremos para esta ruta
//Como se puede ver los mw nos serviran para recibir peticiones que necesitan implementar cierta logica, donde podemos obtener informacion de dichas peticions para evaluarla y hacer una determinada accion  

//OBJETOS REQUES Y RESPONSE
//Request, como anteriormente lo utilizamos, contiene informacion del usuario y lo recibiremos con cada peticion que realice el cliente. Por ejemplo si la peticion viene de un formulario contendra los valores de los atributos name de los inputs del formulario ya sea en get o post. 
//En el objeto REQUEST tiene varias propiedades importantes como server, que contiene inforacion del navegador del cliente, otro es query donde contendra los atributos pasados por url, y podemos acceder al objeto request mediante el helper request() tambien contiene varios metodos como;
// path() para obtener la ruta de acceso,
// tenemos input('name) que le pasamos el name de un input de formulario para obtener su valor. Es importante saber que ya sea por cualquier metodo http se envie el formulario podemos acceder a ellos solo con utilizar input sin importar el metodo
//Mas informacion en: https://laravel.com/docs/7.x/requests
//El objeto RESPONSE se envia impicitamente cuando utilizamos un return dentro del controlador, y es la estrcutura de la respuesta al cliente por ejeplo al devolver un string o una Vista, este sera del tipo Respose. Podemos acceder a su funcionalidad mediante el helper response(), dentro de los metodos importantes tenemos 
//->json([ // que devuelve un objeto json 
//     'status' => 'success'
// ]);
//otro es ->download($pathToFile); //descarga un archivo de nuestro servidor
//El metodo ->with();//Para enviar variables a une cierta respuesta
//Hacer un redireccion on redirect('/') o redirect()->route('posts.create') para usar los nombres de las rutas y no una url especifica
//Mas informacion en https://laravel.com/docs/7.x/responses
//En esta ocasion nuetro contolador ya e le ha inyectado la logica de Request y Response por lo que podemos hacer uso de ellos de manera simple. 

//FORMULARIOS
//en laravel existen paquetes para trabajr con formularios como laravel Collective, que nos permite trabajr con formularios, usando clases, facades de laravel. Pero aqui utilizaremos html puro para nuestro ejercicio.
//VALIDACION DE FORMULARIOS https://laravel.com/docs/7.x/validation
//Sin embargo esto puede ser muy extenso a la hora de definir nuetras validaciones, por ello, laravel nos trae formrequest que podemos crearlas desde artisan asi 'php artisan make:request [UserFormRequest]' esto nos creara un archivo en la carpeta app/Http/R Requests aqui podemos de declarar las reglas de validacion y tener el codigo mejor organizados

//SERVICE CONTAINER
//El contenedor de inyeccion de dependencias o service container es el core de casi cualquier caracteristica en laravel. Es una herramienta que nos permitira instanciar y resolver inancias contcretas de interfaces. Es un contenedor donde registramos las clases que despues nos devolvera una instancia de esas clases en cualquer parte de mi proyecto.
//Otro concepto clave es el ServiceProvider. Es el lugar donde nosotros registramos clases en el seviceContainer este provider se encuentra en app/Provider/AppServiceProvider.php y es aqui donde podemos registrar todas nuestras clases en el contenedor(ServiceConteainer) 
//Imaginemos que estamos haciendo uso de una Api extena como Paypal o Stripe y las tenemos que usar en ciertos metodos de un controlador, osea estariamos intanciando la clase en cada accion del controlador donde se requiera o directamente en el contructor del controlador. Es cuando podemos hacer uso del ServiceContainer y el ServiceProvider para evitar estar creando este objeto cada vez que lo necesitemos. una vez teniendo nuestro modelo Paypal, para poder agregar nuestra clase a un service provider, haremos uso de un serviceProvider y aprovechamos la clase que laravel crea por defecto.
//Las formas de registros en el contenedor pueden ser de dos dos maneras(mas info en https://laravel.com/docs/7.x/providers)
//Las cuales son por bindings o usando el patron de diseño singletons(lo que quiere decir es que cada vez que hagamos uso del contenedor para que nos devuelva una instancia nos devolvera la misma intancia).
// Una vez regitrada nuestra clase Paypal la podemos usar en cualquier parte de nuestro codigo, tambien podemos usar la inyeccion de dependencias para iyectar esa clase en un metodo o un constructor, por ejemplo
Route::get('/paypal', function (/*App\Models\Paypal $paypal*/) {//Primera opcion. Podemos hacer inyeccion de dependencias ya que tenemos registrado la clase en el contenedor gracias al provider de Paypal
    //Primero llamamos al contenedor 
    //$paypal = resolve('App\Models\Paypal');//La segunda opcion. Resolve Recibe un argumento con la ruta de la clase y nos ayudara a obtener la clase del contenedor que definimos en el provider de PaymentProvider
    // return $paypal->doSomething();
    
    //En lugar de inyectar el modelo de Paypal tambien podemos llamar a un Facade que sera el intermediario entre la clase(Paypal) y el contenedor de servicios. Podemos asignarle el alias a dichas Facades en config/app.php, y podras llamar a la clase coo si fuera estatica 
    return Payment::doSomething();//la tercera opcion es hacer llamado por facade. A pesar de marcar error, esta siendo reconocido por laravel ya que 
});
//FACADES(utiliza el mismo ejemplo de arriba) 
// https://laravel.com/docs/7.x/facades
//No son mas que una interfaz entre la clase y el contenedor de laravel. Ya hemos hecho uso de esta sentencia, es y parecido a llamar una funcion estatica usando la clase mas :: y el metodo, ejemplo, Cache::get('key') esto basicamente crea una instancia del objeto ue heos registrado en el contenedor. Para ejemplificar creamos carpeta Facades en app. Dentro creamos una clase PaymetFacade. Luego en config/app.php bscamos la propiedad providers, el cual definira el proveedor si es que creamos uno. Para crear un nuevo provedor usamos artisan: 'php artisan makeLprovider [PaymentProvider]'el cual nos creara un nuevo archivo en app/Provider, una vez definido el povider en el archivo config/app.php agregamos el provider y el alias para el facade.
//Cuándo usar facades(Payment::doSomething()) o inyeccion de indepencdecias(mandar por parametro una intancia Paypal $paypal) o helpers(funciones de laravel)? 
//usa la que ma te guste Helpers y Facades under th hood son practicamente lo mismo, en cuanto ineyeccion de dependencias te permite mandarlo como parametros, al final usa el que mas te convenga

//AUTENTICACION
//En laravel la autenticacion de usuarios viene por defecto, basta con correr 'php artisan make:auth' para generar todos los controladores, vistas y toda la configuracion para poder autenticar users, y es llamado Authentication Quickstart(https://laravel.com/docs/7.x/authentication#authentication-quickstart). A partir de Laravel 6, se implemento un paquete de vue utilizando composer para generar el scaffolding de auth generando todos lo archivos(vistas, contoladores y estructura nueva en archivos de configuracion, asi como en routes en web.php agregara un facade que representa todas las rutas para la autenticacion de usuarios https://laravel.com/docs/7.x/authentication#included-routing) necesarios, para ello necesitamos de los comandos: 'composer require laravel/ui'; despues 'php artisan ui vue --auth'
//Se generaran uevos controladores especificos para la atenticacion en app/Http/controllers/Auth y nuevas vistas en resources/views/auth. Adicionalmente nos agregara en la carpeta resources/js otra cartpeta de components para agregar vue components
Auth::routes();//Esta linea fue generada por los comandos dichos antes y representa todas las rutas para la auth
Route::get('/', 'HomeController@index')->name('home');
//CONFIRMACION DE CORREO
//Primero en nuestro modelo User y hacer que implemente la interfaz MustVerifyEail
//luego modificar nuestra Auth::routes();
// Auth::routes(['verify' => true]);//le pasamos un array 
//Ahora debemos de crear una cuetna de ... para generar los correros que usaremos para confirmar el correo de confirmacion

//PAGINATION
//Checar PostController y la vista post.index
Route::get('/my/posts', 'PostController@myPosts')->name('posts.my');

// POLITICAS
//https://laravel.com/docs/7.x/authorization#creating-policies
//Son clases que organizan logica de autorizacion para un modelo en particular. Como nuestra aplicacion es un tipo blog, podeos hacer politicas para evaluar o autorizar cada una de las acciones del CRUD. Para crear una politica usamos artisan 'php artisan make:policy [PostPolicy] [--model=Models/Post]', creara un archivo en app/Policies, el cual contendra los metodos que contendran la logica de autorizacion, este archivo utilizara el trait HandlesAuthorization; que contiene dos metodos, allow y deny.
//El siguiente paso que debemos hacer es registrar nuestra politica en nuestro AuthServiceProvider.
//Ahora defininimos la logica de nuetros metodos de la politica, 
//Una vez hecho eso modificamos nuestros acciones del controlador usando las politicas 
//Otra cosa importante es que podemos usar middlewares en las mismas politicas que definimos https://laravel.com/docs/7.x/authorization#via-middleware

//GATES
//Laravel tambien nos provee una foma de autorizacion sobre un recurso dentro de nuestro sistema(la primera fue policies) y se llaman gates. Son funciones las cuales validan la autorizacion de cierto recurso, mientras que un policy es una clase en la cual contiene logica de autorizacion(permisos) para un recurso o un controlador. 
//Para implementar gates podemos:
//Ir a nuestro archivo AuthSerrvice providers, el cual tien importado el facade Gate, ahi registramos toda la logica a todos nuestros gates dentro del metodo boot

//ENVIO DE EMAILS https://laravel.com/docs/7.x/mail
//Ejecutamos 'php artisan make:mail [UserWelcome]' esto nos generara un archivo en la carpeta app/Mail, aqui iran las clases que devolveran la vista que definira el cuerpo del correo y otros datos como from, attach, etc. En este archivo nos dara un contructor y un metodo build, en el contructor podemos pasar argumentos a la hora de intanciarlos, definindo propiedades, en este caso enviaremos un email cada vez que un nuevo usurio se registre
//El metodo buil retornara un vista del email, para ello deberemos de definir nuestras vistas en la carpeta de resources/views/mails/, hecho eso debemos de modificar los controladores de registro(app/Http/Controllers/Auth) para propositos de esta funcionalidad

//EVENTOS https://laravel.com/docs/7.x/events
//Laravel nos permite usar eventos a traves de una implemenentacion del patron observable. Un evento se puede ver como un metodo que se tiene que ejecutar cuando cierta accion lo detone. Podemos usar la linea de comando para generar los arcchivos de eventos o bien podemos definir la estructura en el app/Providers/EventServiceProvider. Los archivos de eventos seran conteneores de variables necesarias que se le pasaran a los archivos de los listeners, los cuales implementaran la logica que se realizara cuando se dispare dicho evento

//CREACION DE COMANDOS
// Hasta ahora nos hemos apoyado de la herramienta artisan para generar la mayoria de nuestros archivos con una structura definida. Sin embargo, nosotros tambien podemos definir nuestros ropios comandos, como por ejemplo, generar reportes, enviar email, conultar estados de un servicio, etc. Para generar un comando nos apoyaos de artisan con el comando 'php artisan make:command [UserMail]' lo que nos generara una carpeta app/Console/Commands con un nuevo archivo dentro UserMail.php. Definiendo la logica y estructura de nuestro comando si vamos a la consola y ejecutamos 'php artisan' podemos ver nuestro comando entre la opciones.
// Podemos mandar a llamr nuestro comando con el facade de Artisan
Route::get('/mail', function () {
    Artisan::call('user:mail', [//le pasamos nuestro comando con una array con todas las opciones
        'id' => 1, '--flag' => 'Flag user' 
    ]);
});

//QUEUES https://laravel.com/docs/7.x/queues
//Las colas son un mecanismo para ejecutar una tarea que se toma un tiempo considerable en terminar, Como por ejemplo el envio asivo de emails. Para hacer esto laravel proporciona drivers para hacerlo. 
//Primero el archivo congif/queue.php tiene la configuracion para utilizar colas, en el apartado de connections nos provee de varios drivers y es donde se va almacenar la cola de los trabajos(jobs) que se estan realizando. 
//Para este ejercicio estaremos utilizando el driver para bases de datos.
//Para iniciar cambiamos QUEUE_CONNECTION=database en nuestro .env
//Ahora ejecutamos 'php artisan make:job [UserEmailWelcome]' la cual creara una carpeta app/Jobs conel archivo dentro. Ahi definios nuestra logica. Hecho esto creamos la tabla que contendra la cola con el comando 'php artisan  queue:table' la cual creara una nueva migracion, ahora para ejecutar los cambios rn el esquema sql corremos el comando 'php artisan migrate'.
//Importamos nuestro job
use App\Jobs\UserEmailWelcome;

Route::get('queue', function () {
    //Mandamos a llamar a este job con el metodo dispatch
    // UserEmailWelcome::dispatch(App\User::find(1));//recibe una instancia del usuraio para mandarle el email, y despachara o indexara el job a la tabla jobs 
    //tambien podemos retrasr la tarea con delay
    UserEmailWelcome::dispatch(App\User::find(1))->delay(now()->addSeconds(10));//now, helper
    return "Job indexed";
});//Al hacer una peticion haci esta ruta se, almacenara en la tabla jobs esta tarea, encolando las tereas que lleguen hasta que cada una de las acciones finalice. Si checamos la tabla jobs veremos que las jobs estaran registrados, para realizar esta tareas, podemos utilizar artisan con el comando 'php artisan queue:work' o bien utilizar el facade Artisan::, Esto ejecutara las intancias de jobs en la tabla y se mantendra ejecutando en segundo plano esperando por nuevos registros de jobs

//REDIS https://laravel.com/docs/7.x/redis     https://redis.io/
//implementaremos redis para llevar un conteo de las vistas de un post. 
//Para comenzar, ya teneos la configuracion predefinida en .env, ahora corramos 'redis-cli' para entrar a la ventana de comandos de Redis(Redis es un motor de base de datos en memoria, basado en el almacenamiento en tablas de hashes pero que opcionalmente puede ser usada como una base de datos durable o persistente. Redis es un almacén de estructura de datos en memoria de código abierto (licencia BSD), que se utiliza como agente de base de datos, caché y mensaje. https://redis.io/topics/data-types-intro )
//Vamos a nuestro controlador PorsController para definir la funcionalidad de Redis en la accion show y en su correspondiente vista que mostrara el numero de vistas del post
//Ahora instalamos la libreria de Redis para poder usarlo. Con 'composer require predis/predis'. Si accedemos a nuestra pagina veremos que la funcionalidad de vistas funciona


// TASK SCHEDULING
// https://laravel.com/docs/7.x/scheduling
// Es una forma de ejecutar ciertos comandos o acciones en nuesto servidor cada cierto periodo de tiempo. USaulmente usados para hacer reportes, backups, elimniar registros, cobros masivos, etc. 
// Primero vamos al archivo app/Console/Commands/KErnel.php

// /home/vagrant/code/blog
// * * * * * cd /home/vagrant/code/blog && php artisan schedule:run >> /dev/null 2>&1