<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductosController extends Controller
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
        $productoEstado = 1; // Activo

        $Productos =  Producto::query();

        $Productos->when($productoEstado, function ($q) use ($productoEstado) {
            return $q->where('estado', $productoEstado);
        });

        if ($request->disablePaginate == 0) {
            $Productos = $Productos->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $Productos = $Productos->orderBy('created_at', 'desc')->get();
        }

        //dd( $clientes);
        if (count($Productos) > 0) {
            foreach ($Productos as $key => $Producto) {
                // $Producto->usuarios;
            }

            $response[] = $Productos;
        }

        return response()->json($Productos, $status);
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
            'nombre' => 'required|string|max:160',
            'linea' => 'required|string|max:160',
            'precio1' => 'nullable|numeric|max:17',
            'precio2' => 'required|numeric|max:17',
            'precio3' => 'required|numeric|max:17',
            'precio4' => 'required|numeric|max:17',
            'importacion' => 'required|numeric|max:17',
        ]);


        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }
        // DB::enableQueryLog();

        $Producto = Producto::create([
            'nombre' => $request['nombre'],
            'linea' => $request['linea'],
            'precio1' => $request['precio1'],
            'precio2' => $request['precio2'],
            'precio3' => $request['precio3'],
            'precio4' => $request['precio4'],
            'importacion' => $request['importacion'],
        ]);
        // $query = DB::getQueryLog();
        // dd($query);
        return response()->json([
            'mensaje' => 'Producto insertado con éxito',
            'data' => [
                'id' => $Producto->id,
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

        $Producto =  Producto::where([
            ['id', '=', $id],
        ])->first();

        if (!$Producto) {
            $response = ["mensaje" => "El producto no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $Producto->usuarios;
        $response = ["producto" => $Producto];
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

        $Producto =  Producto::find($id);

        if (!$Producto) {
            $response = ["mensaje" => "El producto no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $validation = Validator::make($request->all(), [
            'nombre' => 'required|string|max:160',
            'linea' => 'required|string|max:160',
            'precio1' => 'nullable|numeric|max:17',
            'precio2' => 'required|numeric|max:17',
            'precio3' => 'required|numeric|max:17',
            'precio4' => 'required|numeric|max:17',
            'importacion' => 'required|numeric|max:17',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }

        $clienteUpdate = $Producto->update([
            'nombre' => $request['nombre'],
            'linea' => $request['linea'],
            'precio1' => $request['precio1'],
            'precio2' => $request['precio2'],
            'precio3' => $request['precio3'],
            'precio4' => $request['precio4'],
            'importacion' => $request['importacion'],
        ]);


        if ($clienteUpdate) {
            $response[] = 'Producto modificado con éxito.';
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
            $response[] = "El Valor de Id debe ser numérico.";
            return response()->json($response, $status);
        }

        $Producto =  Producto::find($id);

        if (!$Producto) {
            $response = ["mensaje" => "El producto no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $ProductoDelete = $Producto->update([
            'estado' => 0,
        ]);

        if ($ProductoDelete) {
            $response[] = 'El producto fue eliminado con éxito.';
            $status = 200;
        } else {
            $response[] = 'Error al eliminar el producto.';
        }

        return response()->json($response, $status);
    }
}
