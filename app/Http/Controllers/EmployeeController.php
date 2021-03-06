<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function index()
    {
        $data = Employee::all();
        if (!empty($data)) {
            $data = $data->load('company');
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
    */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        $data = array_map('trim', $data);
        $rules =[
            'id' =>'required|numeric',
            'nombre' =>'required|alpha',
            'apellido'=>'required|alpha',
            'direccion'=>'required',
            'telefono'=>'numeric',
            'correo'=>'required|email|unique:cliente',
            'tipo'=>'numeric',
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
            $category = new Employee();
            $category->id = $data['id'];
            $category->nombre = $data['nombre'];
            $category->apellido = $data['apellido'];
            $category->direccion = $data['direccion'];
            $category->telefono = $data['telefono'];
            $category->correo = $data['correo'];
            $category->tipo = $data['tipo'];
            $category->save();
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
     * @param  \App\Models\employee  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Employee::find($id);
        if (is_object($data)) {
            $data = $data->load('company'); //me retorna el user que tiene el empleado
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
     * @param  \App\Models\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'id'=>'required',
                'nombre' => 'required|alpha',
                'apellido' => 'required|alpha',
                'direccion' => 'required',
                'telefono' => 'numeric',
                'correo' => 'required|email',
                'tipo' =>'required'
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
                $updated = Employee::where('id', $id)->update($data);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\employee  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $deleted = Employee::where('id', $id)->delete();
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
