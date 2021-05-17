<?php

namespace App\Http\Controllers;

use Illuminate\Models\User;
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
            'idEmpleado'=>'',
            'idCliente'=>'',
            'nivelUsuario'=>'required'


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
            $user->Role=['nivelUsuario'];
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

    public function update(Request $request){

    }
    public function destroy($id){

    }
}
