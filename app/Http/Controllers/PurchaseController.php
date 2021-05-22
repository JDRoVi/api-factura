<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Purchase::all();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        $data = array_map('trim', $data);
        $rules = [
            'idBodeguero' => 'required|numeric',
            'idDetalle' => 'required',
            'idProveedor' => 'required|numeric',
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
            unset($data['fecha']);
            $data['total'] = PurchaseDetails::where('idCompra',$data['idDetalle'])->get()->sum('subtotal');
            $Save = Purchase::where('idDetalle',$data['idDetalle']) -> update($data);
            if($Save > 0){
                $response = array(
                    'status'=>'success',
                    'code'=>200,
                    'message' => 'Datos ingresados correctamente',
                    'data' => $data
                );
            }else{
                $response = array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'No se pudo ingreasar los datos'
                );
            }
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($idPurch)
    {
        $data = Purchase::where('idDetalle', $idPurch)->first();
        if(is_object($data)){
            $data=$data->load('detail');
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

}