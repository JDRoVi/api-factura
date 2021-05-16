<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __Construct(){
        //midd
    }

    //index -->devuelve todos los elementos  GET
    public function index(){
        $data=User::all();
        $response=array(
            'status'=>'success',
            'code'=>200,
            'data'=>$data
        );
        return response()->json($response,200);
    }
     //show--> devuelve un elemento por su id GET
    public function show($id){
        $data=User::find($id);
        if(is_object($data)){
            $data=$data->load('Emply');
            $data=$data->load('Client');

            $response=array(
                'status'=>'success',
                'code'=>200,
                'data'=>$data
            );
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['code']);
    }
    //store --> agrega o guarda un elemnto  POST
    public function store(Request $request){
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rules=[
            'nombreUsuario'=>'required|email|unique:usuario',
            'password'=>'required',
            'nivelUsuario'=>'require'


        ];
        $valid=\validator($data,$rules);

        if($valid->fails()){//se valida un fallo 
            $response=array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Los datos son incorrectos',
                'errors'=>$valid->errors()
            );
        }else{//sin fallos y se procede a agregar
            $user=new User();
            $user->idEmployee=$data['idEmpleado'];
            $user->idClient=$data['idCliente'];
            $user->UserName=$data['NombreUsuario'];
            $user->Role='nivelUsuario';
            $user->email=$data['Correo'];
            $user->password=hash('sha256',$data['contraseña']);
            $user->save();
            $response=array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Datos almacenados exitosamente'
            );
        }
        return response()->json($response,$response['code']);
    }

    /*public function update(Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'id'=>'required',
                //'idCliente'=>'required',
                //'idEmpleado'=>'required',
                'nombreUsuario'=>'required',
                'contraseña' => 'required',
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
                unset($data['idCliente']);
                unset($data['idEmpleado']);
                $updated = User::where('id', $id)->update($data);
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

    }*/

    public function destroy($id){
        if (isset($id)) {
            $deleted = User::where('id', $id)->delete();
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
