<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct()
    {
        
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
            $rules[
                'idEmpleado' => 'required|alpha'
                'nombre' => 'required|alpha'
                'cedulaJuridica' => 'required|alpha'
                'direccion' => 'required|alpha'
                'VolumenVentas' => 'required|alpha' 
            ];
            $validate = \validator($data, $rules);
            if($validate -> fails()){
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $validate -> erros()
                );
            }else{
                $provider = new Provider();
                $provider -> idEmpleado = ['idEmpleado'];
                $provider -> nombre = ['nombre'];
                $provider -> cedulaJuridica = ['cedulaJuridica'];
                $provider -> direccion = ['direccion'];
                $provider -> VolumenVentas = ['VolumenVentas'];
                $provider -> save();
                $response = array(
                    'status' => 'success'
                    'code' => 200,
                    'message' => 'Datos almacenados exitosamente'
                )
            }
        }
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
    public function update(Request $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
