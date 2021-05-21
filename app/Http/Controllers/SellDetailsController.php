<?php

namespace App\Http\Controllers;

use App\Models\SellDetails;
use Illuminate\Http\Request;

class SellDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SellDetails::all();
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
        if(!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'codProducto' => 'required|numeric',
                'idVenta' => 'required|numeric',
                'cantidad' => 'required|numeric',
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
                $sells = Product::where('codigoProducto',$data['codigoProducto'])->first();
                $sellDetails = new SellDetails();
                $sellDetails -> codProducto = $data['codProducto'];
                $sellDetails -> idVenta = $data['idVenta'];
                $sellDetails -> precioUnidad = $sells['precioUnidad'];
                $sellDetails -> cantidad = $data['cantidad'];
                $sellDetails -> subtotal = $sells['subtotal'] * $sells['precioUnidad'];
                $sellDetails -> descuento = $data['descuento'];
                $sellDetails -> save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos almacenados satisfactoriamente'
                );
            }
        } else {
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellDetails  $sellDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = SellDetails::where('idVenta', $codProducto)->get();
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
        return response()->json($response,$response['code']);
    }
}