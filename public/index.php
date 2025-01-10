<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;
use Controllers\PaginasController;
use Controllers\ArbitrosController;
use Controllers\CategoriasController;
use Controllers\PartidosController;
use Controllers\PerfilesController;
use Controllers\DashboardController;
use Controllers\FacturasController;

$router = new Router();


// Login
$router->get('/', [AuthController::class, 'login']);
$router->post('/', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

//ADMINISTRADOR_____________________________________________________________________________________________________________________________
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/partidos', [PartidosController::class, 'index']);
$router->post('/admin/partidos', [PartidosController::class, 'index']);
$router->get('/admin/partidos/subir_partidos', [PartidosController::class, 'subir']);
$router->post('/admin/partidos/subir_partidos', [PartidosController::class, 'subir']);
$router->post('/admin/partidos/truncate_partidos', [PartidosController::class, 'truncate']);
$router->get('/admin/partidos/agregar', [PartidosController::class, 'agregar']);
$router->post('/admin/partidos/agregar', [PartidosController::class, 'agregar']);
$router->get('/admin/partidos/editar', [PartidosController::class, 'editar']);
$router->post('/admin/partidos/editar', [PartidosController::class, 'editar']);
$router->post('/admin/partidos/eliminar', [PartidosController::class, 'eliminar']);
$router->post('/admin/partidos/autocompletar_arbitros', [PartidosController::class, 'autocompletarArbitrosAction']);
$router->post('/admin/partidos/nombrar', [PartidosController::class, 'nombrar']);
$router->post('/admin/partidos/borrar_nombramiento', [PartidosController::class, 'borrar_nombramiento']);
$router->post('/admin/partidos/enviar_partido', [PartidosController::class, 'enviar_partido']);
$router->post('/admin/partidos/autoaceptar', [PartidosController::class, 'autoaceptar']);
$router->get('/admin/partidos/ver_partidos', [PartidosController::class, 'ver_partidos']);

$router->get('/admin/arbitros', [ArbitrosController::class, 'index']);
$router->get('/admin/arbitros/carga_masiva', [ArbitrosController::class, 'carga_masiva']);
$router->post('/admin/arbitros/carga_masiva', [ArbitrosController::class, 'carga_masiva']);
$router->post('/admin/arbitros/truncate_arbitros', [ArbitrosController::class, 'truncate']);
$router->get('/admin/arbitros/agregar', [ArbitrosController::class, 'agregar']);
$router->post('/admin/arbitros/agregar', [ArbitrosController::class, 'agregar']);
$router->get('/admin/arbitros/editar', [ArbitrosController::class, 'editar']);
$router->post('/admin/arbitros/editar', [ArbitrosController::class, 'editar']);
$router->post('/admin/arbitros/eliminar', [ArbitrosController::class, 'eliminar']);
$router->post('/admin/arbitros/actualizar-estado', [ArbitrosController::class, 'actualizar_estado']);

$router->get('/admin/perfiles', [PerfilesController::class, 'index']);
$router->post('/admin/perfiles', [PerfilesController::class, 'index']);
$router->get('/admin/perfiles/agregar', [PerfilesController::class, 'agregar']);
$router->post('/admin/perfiles/agregar', [PerfilesController::class, 'agregar']);
$router->post('/admin/perfiles/actualizar-estado', [PerfilesController::class, 'actualizar_estado']);
$router->get('/admin/perfiles/editar', [PerfilesController::class, 'editar']);
$router->post('/admin/perfiles/editar', [PerfilesController::class, 'editar']);
$router->post('/admin/perfiles/restablecer_password', [PerfilesController::class, 'restablecer_password']);
$router->get('/admin/perfiles/restablecer', [PerfilesController::class, 'restablecer']);
$router->post('/admin/perfiles/restablecer', [PerfilesController::class, 'restablecer']);
$router->post('/admin/perfiles/eliminar', [PerfilesController::class, 'eliminar']);

$router->get('/admin/categorias', [CategoriasController::class, 'index']);
$router->get('/admin/categorias/agregar', [CategoriasController::class, 'agregar']);
$router->post('/admin/categorias/agregar', [CategoriasController::class, 'agregar']);
$router->get('/admin/categorias/editar', [CategoriasController::class, 'editar']);
$router->post('/admin/categorias/editar', [CategoriasController::class, 'editar']);
$router->post('/admin/categorias/eliminar', [CategoriasController::class, 'eliminar']);

$router->get('/admin/facturas', [FacturasController::class, 'index']);
$router->post('/admin/facturas', [FacturasController::class, 'index']);
$router->get('/admin/facturas/generar_jornada', [FacturasController::class, 'generar_jornada']);
$router->post('/admin/facturas/generar_jornada', [FacturasController::class, 'generar_jornada']);
$router->get('/admin/facturas/ver', [FacturasController::class, 'ver']);
$router->post('/admin/facturas/ver', [FacturasController::class, 'ver']);
$router->get('/admin/facturas/editar', [FacturasController::class, 'editar']);
$router->post('/admin/facturas/editar', [FacturasController::class, 'editar']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Formulario de olvide mi password
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

//USUARIOS___________________________________________________________________________________________________
$router->get('/usuario/dashboard', [DashboardController::class, 'index_usuario']);
$router->post('/usuario/dashboard', [DashboardController::class, 'index_usuario']);

//CUALQUIER PERSONA QUE ENTRE A ACEPTAR O RECHAZAR
$router->get('/paginas/aceptar', [PaginasController::class, 'aceptar']);
$router->get('/paginas/motivo_rechazo', [PaginasController::class, 'motivo_rechazo']);
$router->post('/paginas/motivo_rechazo', [PaginasController::class, 'motivo_rechazo']);
$router->get('/paginas/rechazar', [PaginasController::class, 'rechazar']);

$router->comprobarRutas();