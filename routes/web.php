<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::group(['middleware' => ['auth', 'role:developer|area|administracion']], function() {
	Route::get('/planificacion', 'PlanificacionController@Index');

	Route::post('/planificacion/guardar', ['uses' => 'PlanificacionController@store']);
	Route::post('/planificacion/update/{id_info}', ['uses' => 'PlanificacionController@update']);
	Route::post('/planificacion/updategeometry', ['uses' => 'PlanificacionController@updateGeometry']);
	Route::post('/planificacion/baja', ['uses' => 'PlanificacionController@baja']);

	//Route::post('/planificacion/poligonizar', ['uses' => 'PlanificacionController@Poligonizar']);
});

Route::group(['middleware' => ['auth', 'role:developer|area|administracion', 'checkArea:Arbolado,Dpto Técnico']], function() {
	Route::get('/formproblemas', 'FormProblemasController@Index');
	Route::get('/formproblemas/tabla', ['uses' => 'FormProblemasController@IndexTabla']);
	Route::post('/formproblemas/post', ['uses' => 'FormProblemasController@guardar']);
	Route::post('/formproblemas/tabla/post', ['uses' => 'FormProblemasController@Intervenir']);
});
//MAPA TEMATICO
Route::get('/visualizador', 'VisualizadorController@Index');
Route::get('visualizador/puntosxid/{ids_puntos}', ['uses' => 'VisualizadorController@getPointsbyID']);
Route::get('visualizador/poligonosxid/{ids_poligonos}', ['uses' => 'VisualizadorController@getPoligonosbyID']);
Route::get('visualizador/trazasxid/{ids_trazas}', ['uses' => 'VisualizadorController@getTrazasbyID']);

//DASHBOARD
Route::group(['middleware' => ['auth', 'role:developer|area|administracion']], function() {
	Route::get('/dashboard', 'DashboardController@Index');
	Route::get('/dashboard/datosindex', ['uses' => 'DashboardController@getDatosIndex']);
});

//DASHBOARD
Route::group(['middleware' => ['auth', 'role:developer|area|administracion']], function() {
	Route::get('/logistica', 'LogisticaController@Index');
	Route::post('/ordenservicio/nueva', 'LogisticaController@create');
	Route::get('/ordenservicio/listar-planificacion', ['uses' => 'LogisticaController@listarPlanificacion']);
	Route::get('/dashboard/datosindex', ['uses' => 'DashboardController@getDatosIndex']);
});

//SERVICIOS SUA
Route::group(['middleware' => ['auth', 'role:developer'],], function() {
	Route::get('/serviciosua', 'ServiciosSuaController@Index');
	Route::get('/serviciosua/intervenciones', ['uses' => 'ServiciosSuaController@IndexIntervenciones']);
	Route::get('/serviciosua/asignaciones', ['uses' => 'ServiciosSuaController@IndexAsignaciones']);
	Route::get('/serviciosua/resoluciones', ['uses' => 'ServiciosSuaController@IndexResoluciones']);
	Route::get('/serviciosua/cargarids', ['uses' => 'ServiciosSuaController@IndexCargarIDs']);
	Route::post('/serviciosua/procesarids', ['uses' => 'ServiciosSuaController@procesarIds']);

	Route::get('/ajax/getidsolicitudes/{nro}/{anio}', 	['uses' => 'AjaxController@getIdSolicitudes']);
});

Route::group(['middleware' => ['auth', 'role:developer|administracion']], function() {
	Route::get('/admin/dashboard', 'AdministradorController@Index');

	Route::get('/admin/usuarios', 'AdministradorController@GestionarUsuarios');
	Route::get('/admin/usuarios/edit/{id}', ['uses' => 'AdministradorController@EditView']);
	Route::post('/admin/usuarios/editar', ['uses' => 'AdministradorController@EditUpdate']);
	Route::get('/admin/usuarios/registrar', ['uses' => 'AdministradorController@NewUserView']);
	Route::post('/admin/usuarios/nuevo', ['uses' => 'AdministradorController@NewUserCreate']);
	Route::post('/admin/usuarios/delete', ['uses' => 'AdministradorController@DeleteUser']);

	Route::get('/admin/datoscomplementarios', ['uses' => 'AdministradorController@DatosView']);
	Route::get('/admin/datoscomplementarios/nuevo', ['uses' => 'AdministradorController@NewDatosView']);
	Route::post('/admin/datoscomplementarios/nuevo/post', ['uses' => 'AdministradorController@NewDato']);

	Route::get('/admin/etiquetas', ['uses' => 'AdministradorController@GestionarEtiquetas']);
	Route::post('/admin/etiquetas/delete', ['uses' => 'AdministradorController@DeleteEtiqueta']);
	Route::get('/admin/etiquetas/edit/{id}', ['uses' => 'AdministradorController@EditViewEtiquetas']);
	Route::post('/admin/etiquetas/editar', ['uses' => 'AdministradorController@EditUpdateEtiquetas']);
	Route::get('/admin/etiquetas/nueva', ['uses' => 'AdministradorController@NewEtiquetaView']);
	Route::post('/admin/etiquetas/nueva/post', ['uses' => 'AdministradorController@NewEtiquetaCreate']);

	Route::get('/admin/estilos', ['uses' => 'AdministradorController@GestionarEstilos']);
	Route::get('/admin/estilos/edit/{id}', ['uses' => 'AdministradorController@EditViewEstilos']);
	Route::post('/admin/estilos/editar', ['uses' => 'AdministradorController@EditUpdateEstilos']);

	Route::get('/admin/equipo', ['uses' => 'AdministradorController@GestionarEquipo']);
	Route::get('/admin/equipo/nuevo', ['uses' => 'AdministradorController@NewEquipoView']);
	Route::get('/admin/equipo/editar/{id}', ['uses' => 'AdministradorController@EditEquipoView']);
	Route::post('/admin/equipo/editar/post', ['uses' => 'AdministradorController@EditEquipoUpdate']);
	Route::post('/admin/equipo/nuevo/post', ['uses' => 'AdministradorController@NewEquipoConfig']);
	Route::post('/admin/equipo/delete', ['uses' => 'AdministradorController@DeleteEquipo']);

	Route::get('/admin/areasconfig', ['uses' => 'AdministradorController@GestionarAreasConfig']);
	Route::get('/admin/areasconfig/nuevo', ['uses' => 'AdministradorController@NewAreaView']);
	Route::get('/admin/areasconfig/editar/{id}', ['uses' => 'AdministradorController@EditAreaView']);
	Route::post('/admin/areasconfig/editar/post', ['uses' => 'AdministradorController@EditAreaUpdate']);
	Route::post('/admin/areasconfig/nuevo/post', ['uses' => 'AdministradorController@NewAreaConfig']);
	Route::post('/admin/areasconfig/delete', ['uses' => 'AdministradorController@DeleteArea']);


	Route::get('/admin/capasutiles', ['uses' => 'AdministradorController@GestionarCapasUtiles']);
	Route::get('/admin/capasutiles/nuevo', ['uses' => 'AdministradorController@NewCapaView']);
	Route::post('/admin/capasutiles/nuevo/post', ['uses' => 'AdministradorController@NewCapa']);
	Route::get('/admin/capasutiles/edit/{id}', ['uses' => 'AdministradorController@EditCapaView']);
	Route::post('/admin/capasutiles/editar', ['uses' => 'AdministradorController@EditCapaUpdate']);

	Route::get('/admin/exportar', ['uses' => 'AdministradorController@Exportar']);

	Route::get('/admin/importar', ['uses' => 'AdministradorController@Importar']);
	
});

//RESPUESTAS AJAX JSON/ARRAY

Route::get('ajax/geojson', ['uses' => 'AjaxController@getGeojson']);
Route::get('/ajax/planificacionpoints/{area}', ['uses' => 'AjaxController@getPlanificacionPoints']);
Route::get('/ajax/planificacionpolygons/{area}', ['uses' => 'AjaxController@getPlanificacionPolygons']);
Route::get('/ajax/planificacionpolylines/{area}', ['uses' => 'AjaxController@getPlanificacionPolylines']);
Route::get('/ajax/puntosdehoy', ['uses' => 'AjaxController@getPuntosdeHoy']);
Route::get('/ajax/poligonosdehoy', ['uses' => 'AjaxController@getPoligonosdeHoy']);
Route::get('/ajax/lineasdehoy', ['uses' => 'AjaxController@getLineasdeHoy']);
Route::get('/ajax/tags', ['uses' => 'AjaxController@getTags']);
Route::get('/ajax/tags-grupo/{filtro}', ['uses' => 'AjaxController@getTagsByGroup']);
Route::get('/ajax/grupos', ['uses' => 'AjaxController@getGrupos']);
Route::get('/ajax/roles', ['uses' => 'AjaxController@getRoles']);
Route::get('/ajax/cache', ['uses' => 'AjaxController@getCache']);
Route::get('/ajax/legend', ['uses' => 'AjaxController@getLegend']);
Route::get('/ajax/capasutiles', ['uses' => 'AjaxController@getCapasUtiles']);

Route::get('/ajax/estilo_capa', ['uses' => 'AjaxController@getEstilosCapas']);
Route::get('/ajax/formtable', ['uses' => 'AjaxController@getDatosFormTable']);
Route::get('/ajax/datainter', ['uses' => 'AjaxController@getDatosIntervenciones']);


Route::post('/ajax/puntos', ['uses' => 'AjaxController@getPuntos']);
Route::post('/ajax/poligonos', ['uses' => 'AjaxController@getPoligonos']);
Route::post('/ajax/lineas', ['uses' => 'AjaxController@getLineas']);

Route::get('/ajax/visualizadordetails/{id}', ['uses' => 'AjaxController@getDetailsGeometries']);
Route::get('/ajax/datos_complementarios_desclarga', ['uses' => 'AjaxController@getDatosDescLarga']);

//SERVICIOSUA
Route::get('/ajax/serviciosua/token', ['uses' => 'ServiciosSuaController@getToken']);
Route::get('/ajax/serviciosua/id_intervenciones/', ['uses' => 'ServiciosSuaController@getIdIntervenciones']);
Route::get('/ajax/serviciosua/id_asignaciones/', ['uses' => 'ServiciosSuaController@getIdAsignaciones']);
Route::post('/ajax/serviciosua/asignar', ['uses' => 'ServiciosSuaController@Asignar']);
Route::post('/ajax/serviciosua/intervenir', ['uses' => 'ServiciosSuaController@Intervenir']);
Route::post('/ajax/serviciosua/resolver', ['uses' => 'ServiciosSuaController@Resolver']);

//Datatables
Route::get('/datatables/geometrias', ['uses' => 'DatatablesController@Geometrias']);
Route::get('/datatables/usuarios', ['uses' => 'DatatablesController@Usuarios']);
Route::get('/datatables/etiquetas', ['uses' => 'DatatablesController@Etiquetas']);
Route::get('/datatables/estilos', ['uses' => 'DatatablesController@Estilos']);
Route::get('/datatables/areasconfig', ['uses' => 'DatatablesController@AreasConfig']);
Route::get('/datatables/equipo', ['uses' => 'DatatablesController@Equipo']);
Route::get('/datatables/capasutiles', ['uses' => 'DatatablesController@CapasUtiles']);
Route::get('/datatables/dash', ['uses' => 'DatatablesController@getInfo']);
Route::get('/datatables/dashgraph', ['uses' => 'DatatablesController@getInfoForGraph']);
Route::get('/datatables/tablaArea', ['uses' => 'DatatablesController@tablaArea']);
Route::get('/datatables/datoscomplementarios/{id}', ['uses' => 'DatatablesController@DatosComplementarios']);
Route::get('/datatables/datoscomplementariosall', ['uses' => 'DatatablesController@DatosComplementariosAll']);
//prueba pdf
Route::post('/pdf', ['uses' => 'PdfController@invoice']);

Route::get('/admin/calendarValidation', ['uses' => 'AdministradorController@calendarValidation']);
Route::get('/admin/areaTable',['uses' => 'AdministradorController@areaTable']);
Route::get('/datatables/test', ['uses' => 'DatatablesController@getAreasPorDia']);
Route::get('/graphs/interv',['uses' => 'DatatablesController@getIntervData']);
Route::get('/visualizador/getgg', ['uses' => 'DatatablesController@makeGg']);
