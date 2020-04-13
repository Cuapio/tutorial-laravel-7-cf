<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()//Vamos a delegar esta accion a las politicas de laravel depsues, cuando veamos la autorizacion
    {
        return true;//De mientras retornamos true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:10',//en un string definimos todas los reglas de validacion concatenando con el simbolo |
            'content' => 'required|min:5|max:50'
        ];
    }

    //para scribir los mensajes que se mostraran podemos sobreescribir el metodo messages
    public function messages() {
        return [
            'title.required' => 'El titulo es requerido'//Usamos el nombre de la varibale y la regla y depsues le asignaos una descripcion 
            //...los demas mensajes
        ];
    }
}
