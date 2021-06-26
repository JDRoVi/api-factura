<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::all();
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
                'CodigoProducto'=>'required|unique:producto',
                'idprovedor' => 'required|numeric',
                'nombre' => 'required|alpha',
                'cantidad' => 'required|numeric',
                'fechaCaducidad' => 'required',
                'precioUnidad' => 'required'
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
                $product = new Product();
                $product -> CodigoProducto = $data['CodigoProducto'];
                $product -> idprovedor = $data['idprovedor'];
                $product -> nombre = $data['nombre'];
                $product -> cantidad = $data['cantidad'];
                $product -> fechaCaducidad = $data['fechaCaducidad'];
                $product -> precioUnidad = $data['precioUnidad'];
                $product -> save();
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
        return response()->json($response,$response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($codigo)
    {
        $data=Product::where('CodigoProducto',$CodigoProducto)->first();
        if(is_object($data)){
            $data = $data->load('Provider');
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $json = $request -> input('json', null);
        $data = json_decode($json,true);
        if(!empty($data)){
            $data = array_map('trim', $data);
            $rules = [
                'idprovedor' => 'required',
                'nombre' => 'required|alpha',
                'cantidad' => 'required|numeric',
                'fechaCaducidad' => 'required',
                'precioUnidad' => 'required'
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
                $codigo = $data['CodigoProducto'];
                unset($data['id']);
                unset($data['CodigoProducto']);
                unset($data['created_at']);
                $updated = Product::where('CodigoProducto',$codigo) -> update($data);
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset($id)){
            $deleted = Product::where('id', $id) -> delete();
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
            \Storage::disk('producto')->put($image_name,\File::get($image));
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
        $exist=\storage::disk('producto')->exists($filename);
        if($exist){
            $file = \Storage::disk('producto')->get($filename);
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