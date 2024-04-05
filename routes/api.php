<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductosController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//register new user

Route::post('/create-account', [AuthenticationController::class, 'createAccount']);
//login user
Route::post('/signin', [AuthenticationController::class, 'signin']);

//using middleware
Route::group(['middleware' => ['auth:sanctum', 'role:administrador|vendedor|supervisor']], function () {
    Route::post('/sign-out', [AuthenticationController::class, 'signout']);
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });
});

Route::get('script/AsignarPrecioPorUnidadGlobal', [ScriptController::class, 'AsignarPrecioPorUnidadGlobal']);
Route::get('script/validarStatusPagadoGlobal', [ScriptController::class, 'validarStatusPagadoGlobal']);
Route::get('script/actualizarPrecioFactura/{id}', [ScriptController::class, 'ActualizarPrecioFactura']);
Route::get('script/validar-meta-recuperacion', [ScriptController::class, 'validarMetaRecuperacion']);


// Route::group(['middleware' => ['auth:sanctum', 'role:administrador|vendedor|supervisor', 'cierre']], function () {

    Route::get('cliente/factura/{id}',  [ClienteController::class, 'clienteToFactura']);
    Route::get('cliente/abono/{id}',  [ClienteController::class, 'calcularAbono']);
    Route::get('cliente/deuda/{id}',  [ClienteController::class, 'calcularDeudaVendedorCliente']);
    Route::get('cliente/deuda',  [ClienteController::class, 'calcularDeudaVendedorTodosClientes']);
    Route::get('cliente/deuda/user/{id}',  [ClienteController::class, 'calcularDeudaVendedorTodosClientesPorUsuario']);
    Route::get('cliente/usuario/{id}',  [ClienteController::class, 'clientesVendedor']);
    Route::resource('cliente', ClienteController::class);

    Route::resource('roles', RoleController::class);

    Route::resource('usuarios', UsuarioController::class);
    Route::put('update-password/{id}',  [UsuarioController::class, 'updatePassword']);


    Route::resource('productos', ProductosController::class);

// });

Route::get('configuracion/crons', function () {
    Artisan::call('schedule:run');
    // Artisan::call('reset:categorys');
    echo Artisan::output();
});

Route::get('configuracion/crons-list', function () {
    //    Artisan::call('reset:categorys');
    Artisan::call('schedule:list');

    echo Artisan::output();
});

Route::get('configuracion/clear-cache', function () {
    echo Artisan::call('config:clear');
    echo Artisan::call('config:cache');
    echo Artisan::call('cache:clear');
    echo Artisan::call('route:clear');

    //  Artisan::call('schedule:list');
    //echo Artisan::output();
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
