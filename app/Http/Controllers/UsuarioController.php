<?php

namespace App\Http\Controllers;

// use App\Models\Recibo;
// use App\Models\ReciboHistorial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Exception;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $status = 200;

        $usuarios =  User::query();
        
        $usuarios->when(isset($request->estado), function ($q) use ($request) {
            return $q->where('estado', $request->estado);
        });

        if ($request->disablePaginate == 0) {
            $usuarios = $usuarios->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $usuarios = $usuarios->orderBy('created_at', 'desc')->get();
        }

        foreach ($usuarios as $usuario) {
            $usuario->clientes;
            $role_id = DB::table('model_has_roles')->where('model_id', $usuario->id)->first();
            // $usuario->role_id = $role_id->role_id;
            $usuario->rolesUser;
        }

        $response = $usuarios;
        $status = 200;

        return response()->json($response, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'nombre_completo' => 'required|string|max:160',
            'user' => 'required|string|max:60',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|max:255',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'nombre_completo' => $request['nombre_completo'],
                'user' => $request['user'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ]);

            $role = Role::find($request['role']);

            $user->assignRole($role->name);
            $user->createToken('tokens')->plainTextToken;
            DB::commit();

            return response()->json([
                'id' => $user->id,
            ], 201);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(["mensaje" => $e->getMessage()], 400);
        }


        return response()->json([
            'id' => $user->id,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $response = [];
        $status = 400;

        if (!is_numeric($id)) {
            $response = ["mensaje" => "El Valor de Id debe ser numérico."];
            return response()->json($response, $status);
        }

        $usuario  =  User::where([
            ['id', '=', $id],
            // ['estado', '=', $clienteEstado],
        ])->first();

        if (!$usuario) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $role_id = DB::table('model_has_roles')->where('model_id', $usuario->id)->first();

        $usuario->clientes;
        $usuario->role_id = $role_id->role_id;

        $response = $usuario;
        $status = 200;
        return response()->json($response, $status);
    }

    public function edit(UsuarioController $usuarioController)
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

        $usuario =  User::find($id);

        if (!$usuario) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }

        $validation = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:160',
            'user' => 'required|string|max:60',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            // 'password' => 'required|string|max:255',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }

        $usuarioUpdate = $usuario->update([
            'nombre_completo' => $request['nombre_completo'],
            'user' => $request['user'],
            'email' => $request['email'],
            // 'password' => bcrypt($request['password']),
        ]);

        DB::table('model_has_roles')->where('model_id', $usuario->id)->delete();
        $role = Role::find($request['role']);

        $usuario->assignRole($role->name);

        if ($usuarioUpdate) {
            $response[] = 'Usuario modificado con éxito.';
            $status = 200;
        } else {
            $response[] = 'Error al modificar los datos.';
        }

        return response()->json($response, $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword($id, Request $request)
    {
        $response = [];
        $status = 400;

        if (!is_numeric($id)) {
            $response = ["mensaje" => "El Valor de Id debe ser numérico."];
            return response()->json($response, $status);
        }
        $usuario =  User::find($id);
        // dd($usuario);
        if (!$usuario) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }
        $validation = Validator::make($request->all(), [
            'password' => 'required|string|confirmed',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        }

        $usuarioUpdate = $usuario->update([
            'password' => bcrypt($request['password']),
        ]);

        if ($usuarioUpdate) {
            $response = ["mensaje" => "Clave modificada con éxito"];
            $status = 200;
        } else {
            $response = ["mensaje" => "Error al modificar la clave"];
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
            $response = ["mensaje" => "El Valor de Id debe ser numérico."];
            return response()->json($response, $status);
        }

        $usuario =  User::find($id);

        if (!$usuario) {
            $response = ["mensaje" => "El cliente no existe o fue eliminado."];
            return response()->json($response, $status);
        }
        $usuarioDelete = $usuario->update([
            'estado' => 0,
        ]);

        if ($usuarioDelete) {
            $response = ["mensaje" => "El usuario fue eliminado con éxito."];
            $status = 200;
        } else {
            $response[] = 'Error al eliminar el usuario.';
        }

        return response()->json($response, $status);
    }
}
