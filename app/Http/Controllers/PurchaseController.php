<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

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
            'id' => 'required|numeric',
            'idBodeguero' => 'required|numeric',
            'idDetalleVenta' => 'required|numeric',
            'idProveedor' => 'required|numeric',
            'fecha' => 'required',
            'total' => 'required|numeric'
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
            $purchase = new Purchase();
            $purchase -> id = $data['id'];
            $purchase -> idBodeguero = $data['idBodeguero'];
            $purchase -> idDetalleVenta = $data['idDetalleVenta'];
            $purchase -> idProveedor = $data['idProveedor'];
            $purchase -> fecha = $data['fecha'];
            $purchase -> total = $data['total'];
            $purchase -> save();
            $response = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos almacenados satisfactoriamente'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Purchase::find($id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [];
            $validate = \validator($data, $rules);
            if ($validate->fails()) {
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos enviados son incorrectos',
                    'errors' => $validate->errors()
                );
            } else {
                $id=$data['id'];
                unset($data['id']);
                unset($data['created_at']);
                $updated = Purchase::where('id', $id)->update($data);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $deleted = Purchase::where('id', $id)->delete();
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
