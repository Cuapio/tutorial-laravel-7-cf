<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Primeramente eliminaremos los registros existentes para evitar errores en el llenado 
        $this->truncatetables([//recibira un array con todas las tablas que seran llenadas con los seeders
            'users',
            'posts',
            'comments'
        ]);
        //De esta manera reisramos nuestros seeders
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
    }

    //Este metodo nos permitira eliminar los registros existentes en las tablas, para despues llenarlos de nuevo cada vez que ejecutemos los seeders. Para ello primero se ejecut esta funcion antes de ejecutar los seeders yh se define en el metoo run()
    public function truncatetables(array $tables) {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');//deshabilitamos la restriccion de llaves foraneas para que nos permita realizar cambios al eliminar los registros de todas las tablas

        foreach($tables as $table) {//Eliminaos los registros de las tablas actuales
            DB::table($table)->truncate();//Eliminara los registros de la tabla actual
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');//Volvemos activar la restriccion de llaves foraneas
    }
}
