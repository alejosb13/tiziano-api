<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $status = 200;

        $clientes =  Cliente::query();

        $clientes->when(isset($request->estado), function ($q) use ($request) {
            return $q->where('estado', $request->estado);
        });

        if ($request->disablePaginate == 0) {
            $clientes = $clientes->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $clientes = $clientes->orderBy('created_at', 'desc')->get();
        }

        //dd( $clientes);
        if (count($clientes) > 0) {
            foreach ($clientes as $cliente) {
                $cliente->usuarios;
            }

            $response[] = $clientes;
        }

        return response()->json($clientes, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nombreCompleto' => 'required|string|max:160',
            'correo' => 'required|string|email|max:160|unique:clientes,correo',
            'telefono' => 'nullable|numeric',
            'direccion' => 'required|string|max:180',
            'persona_contacto' => 'nullable|max:180',
            // 'estado' => 'required|numeric|max:1',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }
        // DB::enableQueryLog();

        $Cliente = Cliente::create([
            'nombreCompleto' => $request['nombreCompleto'],
            'correo' => $request['correo'],
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'persona_contacto' => $request['persona_contacto'],
        ]);
        // $query = DB::getQueryLog();
        // dd($query);
        return response()->json([
            'mensaje' => 'Usuario Insertado con éxito',
            'data' => [
                'id' => $Cliente->id,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $response = null;
        $status = 400;
        // $clienteEstado = 1; // Activo

        if (!is_numeric($id)) {
            $response = ["mensaje" => "El Valor de Id debe ser numérico."];
            return response()->json($response, $status);
        }

        $cliente =  Cliente::where([
            ['id', '=', $id],
            // ['estado', '=', $clienteEstado],
        ])->first();

        if (!$cliente) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $cliente->usuarios;
        $response = ["cliente" => $cliente];
        $status = 200;
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = [];
        $status = 400;

        if (!is_numeric($id)) {
            $response = ["mensaje" => "El Valor de Id debe ser numérico."];
            return response()->json($response, $status);
        }

        $cliente =  Cliente::find($id);

        if (!$cliente) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $validation = Validator::make($request->all(), [
            'nombreCompleto' => 'required|string|max:160',
            'correo' => 'required|string|email|max:160|unique:clientes,correo,' . $id,
            'telefono' => 'nullable|numeric',
            'direccion' => 'required|string|max:180',
            'persona_contacto' => 'nullable|max:180',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }

        $clienteUpdate = $cliente->update([
            'nombreCompleto' => $request['nombreCompleto'],
            'correo' => $request['correo'],
            'telefono' => $request['telefono'],
            'direccion' => $request['direccion'],
            'persona_contacto' => $request['persona_contacto'],
        ]);


        if ($clienteUpdate) {
            $response = ["mensaje" => "Cliente modificado con éxito."];
            $status = 200;
        } else {
            $response[] = 'Error al modificar los datos.';
        }

        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = [];
        $status = 400;

        if (!is_numeric($id)) {
            $response["mensaje"] = "El Valor de Id debe ser numérico.";
            return response()->json($response, $status);
        }

        $cliente =  Cliente::find($id);

        if (!$cliente) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $clienteDelete = $cliente->update([
            'estado' => 0,
        ]);

        if ($clienteDelete) {
            $response = ["mensaje" => "El cliente fue eliminado con éxito."];
            $status = 200;
        } else {
            $response["mensaje"] = 'Error al eliminar el cliente.'; 
        }

        return response()->json($response, $status);
    }
}
