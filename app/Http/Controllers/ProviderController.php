<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct()
    {
        //mvm
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Provider::all();
        $response = array(
            'status' => 'success',
            'code' => 200,
            'data' => $data
        );
        return response() -> json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request -> input('json', null);
        $data = json_decode($json, true);
        if(!empty($data)){
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required|numeric',
                'idEmpleado' => 'required|numeric',
                'nombre' => 'required|alpha',
                'cedulaJuridica' => 'required|numeric',
                'direccion' => 'required',
                'VolumenVentas' => 'required|numeric'
            ];
            $validate = \validator($data, $rules);
            if($validate -> fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $validate -> errors()
                );
            }else{
                $provider = new Provider();
                $provider -> id = $data['id'];
                $provider -> idEmpleado = $data['idEmpleado'];
                $provider -> nombre = $data['nombre'];
                $provider -> cedulaJuridica = $data['cedulaJuridica'];
                $provider -> direccion = $data['direccion'];
                $provider -> VolumenVentas = $data['VolumenVentas'];
                $provider -> save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos almacenados exitosamente'
                );
            }
        }
        return response()->json($response,$response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=Provider::find($id);
        if(is_object($data)){
            $data=$data->load('products');
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $json = $request -> input('json', null);
        $data = json_decode($json, true);
        if(!empty($data)){
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required',
                'idEmpleado' => 'required',
                'nombre' => 'required',
                'cedulaJuridica' => 'required|numeric',
                'direccion' => 'required',
                'VolumenVentas' => 'required|numeric'
            ];
            $validate = \validator($data, $rules);
            if($validate -> fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos introducidos son incorrectos',
                    'errors' => $validate -> errors()
                );
            }else{
                $id = $data['id'];
                unset($data['id']);
                //unset($data['idEmpleado']);
                unset($data['created_at']);
                $updated = Provider::where('id',$id) -> update($data);
                if($updated > 0){
                    $response = array(
                        'status'=>'success',
                        'code'=>200,
                        'message' => 'Datos actualizados correctamente'
                    );
                }else{
                    $response = array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'No se pudo actualizar los datos'
                    );
                }
            }
        }else{
            $response = array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Faltan parametros'
            );
        }
        return response() -> json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset($id)){
            $deleted = Provider::where('id', $id) -> delete();
            if($deleted){
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'message' => 'Datos eliminados correctamente'
                );
            }else{
                $response = array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Problema al eliminar datos, puede que el recurso no exista'
                );
            }
        }else{
            $response = array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Falta el identificador de los datos'
            );
        }
        return response() -> json($response, $response['code']);
    }
}