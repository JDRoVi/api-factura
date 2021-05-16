<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        //no data
    }

    //index -->devuelve todos los elementos  GET
    public function index()
    {
        $data = Client::all();
        if (!empty($data)) {
            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Recurso Vacio o no Encontrado'
            );
        }
        return response()->json($response, $response['code']);
    }

    //show--> devuelve un elemento por su id GET
    public function show($id)
    { //busqueda
        $data = Client::find($id);
        $data=$data->load('user');
        if (is_object($data)) {
           // $data=$data->load('user'); //me retorna el user que tiene el cliente 
            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }

    //store --> agrega o guarda un elemnto  POST
    public function store(Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        $data = array_map('trim', $data);
        $rules =[
            'id' =>'required|numeric',
            'nombre' =>'required|alpha',
            'apellido'=>'required|alpha',
            'direccion'=>'required',
            'telefono'=>'numeric',
            'correo'=>'required|email|unique:cliente'
        ];

        $valid = \validator($data, $rules);
        if ($valid->fails()) {
            $response = array(
                'status' => 'error',
                'code' => 406,
                'message' => 'Los datos son incorrectos',
                'errors' => $valid->errors()
            );
        } else {
            $category = new Client();
            $category->id = $data['id'];
            $category->nombre = $data['nombre'];
            $category->apellido = $data['apellido'];
            $category->direccion = $data['direccion'];
            $category->telefono = $data['telefono'];
            $category->correo = $data['correo'];
            $category->save();
            $response = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos almacenados satisfactoriamente'
            );
        }

        return response()->json($response, $response['code']);
    }

    //update --> modifica un elemento    PUT
    public function update(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'id'=>'required',
                'nombre' => 'required|alpha',
                'apellido' => 'required|alpha',
                'direccion' => 'required',
                'telefono' => 'numeric',
                'correo' => 'required|email'
            ];
            $validate = \validator($data, $rules);
            if ($validate->fails()) { //determina si los datos siguen las reglas
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos del cliente enviados son incorrectos',
                    'errors' => $validate->errors()
                );
            } else {
                $id=$data['id'];
                unset($data['id']);
                unset($data['created_at']);
                $updated = Client::where('id', $id)->update($data);
                if ($updated > 0) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos actualizados exitosamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudo actualizar los datos'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Faltan Datos'
            );
        }
        return response()->json($response,$response['code']);
    }

    //destroy --> Elimina un elemento   DELETE
    public function destroy($id)
    {
        if (isset($id)) {
            $deleted = Client::where('id', $id)->delete();
            if ($deleted) {
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Eliminado correctamente'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Problemas al eleminar el recurso, puede ser que el recurso no exista'
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Falta el identificador del recurso'
            );
        }
        return response()->json($response, $response['code']);
    }
}
