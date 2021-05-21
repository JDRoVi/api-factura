<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDetails;
use Illuminate\Http\Request;
use App\Models\Product;

class PurchaseDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PurchaseDetails::all();
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
        if(!empty($data)){
        $data = array_map('trim', $data);
        $rules = [
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
            $purch = Product::where('codigoProducto',$data['codigoProducto'])->first();
            $PurchaseDetails = new PurchaseDetails();
            $PurchaseDetails -> codigoProducto = $data['codigoProducto'];
            $PurchaseDetails -> idCompra = $data['idCompra'];
            $PurchaseDetails -> precioUnidad = $purch['precioUnidad'];
            $PurchaseDetails -> cantidad = $data['cantidad'];
            $PurchaseDetails -> subtotal = $data['cantidad'] * $purch['precioUnidad'];
            $PurchaseDetails -> save();
            $response = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos almacenados satisfactoriamente'
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseDetails  $purchaseDetails
     * @return \Illuminate\Http\Response
     */
    public function show($CodigoVenta)
    {
        $data = PurchaseDetails::where('idCompra', $CodigoVenta)->get();
        if (is_object($data)) {
            $data = $data->load('purch');
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
