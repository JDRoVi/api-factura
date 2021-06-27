<?php

namespace App\Http\Controllers;

use App\Models\User;
use app\Helpers\JwtAuth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
      $this->middleware('api.auth',['except'=>['login','store']]);
    }

    public function __invoke(){
        
    }

    //index -->devuelve todos los elementos  GET
    public function index()
    {
        $data = User::all();
        $response = array(
            'status' => 'success',
            'code' => 200,
            'data' => $data
        );
        return response()->json($response, 200);
    }
    //show--> devuelve un elemento por su id GET
    public function show($id)
    {
        $data = User::find($id);
        if (is_object($data)) {
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
        return response()->json($response, $response['code']);
    }
    //store --> agrega o guarda un elemnto  POST
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'idEmpleado' => 'required',
                'nombreUsuario' => 'required|unique:Usuario',
                'password' => 'required',
                'nivelUsuario' => 'required'
            ];
            $valid = \validator($data, $rules);
            if ($valid->fails()) { //se valida un fallo 
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $valid->errors()
                );
            } else { //sin fallos y se procede a agregar
                    $user = new User();
                    $user->idEmpleado = $data['idEmpleado'];
                    $user->nombreUsuario = $data['nombreUsuario'];
                    $user->password = hash('sha256', $data['password']);
                    $user->nivelUsuario = $data['nivelUsuario'];
                    $user->save();
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos almacenados exitosamente'
                    );
                }
        }else{
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);
    }

    public function update(Request $request){
        $json = $request->input('json',null);
        $data= json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'nombreUsuario' => 'required',
                'password' => 'required',
            ];
            $valid = \validator($data, $rules);
            if ($valid->fails()) { //se valida un fallo 
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $valid->errors()
                );
            } else {
                $userName = $data['nombreUsuario'];
                 unset($data['id']);
                 unset($data['idEmpleado']);
                 unset($data['idCliente']);
                 unset($data['create_at']);
                 $data['password'] = hash('sha256',$data['password']);//se cifra la nueva contraseña
                 $updated=User::where('nombreUsuario',$userName)->update($data);
                 if($updated>0){
                     $response=array(
                         'status'=>'success',
                         'code'=>200,
                         'message'=>'Actualizado correctamente'
                     );
                 }else{
                     $response=array(
                         'status'=>'error',
                         'code'=>400,
                         'message'=>'No se pudo actualizar, puede que el usuario no exita'
                     );
                 }
            }

        }else{
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);

    }

    public function destroy($id){
        if(isset($id)){
            $deleted=User::where('id',$id)->delete();
            if($deleted){
                $response=array(
                    'status'=>'success',
                    'code'=>200,
                    'message'=>'Eliminado correctamente'
                    );
            }else{
                $response=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo eliminar'
                    );
            }
        }
        else{
            $response=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Falta el identificador del recurso'
                );
        }
        return response()->json($response,$response['code']);
    }

    public function login(Request $request){//metodo de ingreso desde donde se usa el jwt
        $jwtAuth = new JwtAuth();
        $json=$request->input('json',null);
        $data=json_decode($json,true);
        $data=array_map('trim',$data);
        $rule=[
            'nombreUsuario' => 'required',
            'password' => 'required',
        ];
        $validated = \validator($data,$rule);
        if($validated->fails()){
            $response = array(
                'status'=>'error',
                'code'=>'406',
                'message'=>'Los datos enviados son incorrectos',
                'errors'=>$validated->errors()
            );
        }else{
            $response = $jwtAuth->signin($data['nombreUsuario'],$data['password']);
        }
        if(isset($response['code'])){
            return response()->json($response,$response['code']);
        }else{
            return response()->json($response,200);
        }
        
    }

    public function getIdentity(Request $request){//se valida la identidad
        $jwtAuth = new JwtAuth();
        $token = $request->header('token');
        $response = $jwtAuth->verify($token,true);
        return response()->json($response);
    }

    public function uploadImage(Request $request)
    {
        $image = $request->file('file0');
        $validate=\Validator::make($request->all(),[
        'file0'=>'required|image|mimes|:jpg,jpeg,png']);
        if($validate->fails()){
            $response = array(
                'status'=>'error',
                'code'=>406,
                'message'=>'Error al subir la imagen',
                'errors'=>$validate->errors()
            );
        }else{
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('usuario')->put($image_name,\File::get($image));
            $response = array(
                'status'=>'success',
                'code'=>200,
                'message'=>'Imagen almacenada exitosamente',
                'image'=>$image_name
            );
        }
        return response()->json($response,$response['code']);
    }

    public function getImage($filename)
    {
        echo($filename);
        $exist=\storage::disk('usuario')->exists($filename);
        if($exist){
            $file = \Storage::disk('usuario')->get($filename);
            return new Response($file,2000);
        }else{
            $response=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'Recurso/imagen no existe'
            );
        }
    }

}