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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['middleware' => ['web', 'auth', 'role:developer|area|administracion']], function() {
	Route::get('/planificacion', 'PlanificacionController@Index')->name('planificacion');
	Route::group(['prefix' => 'planificacion'], function(){
		Route::post('/guardar', 'PlanificacionController@store')->name('planificacion_guardar');
		Route::post('/update/{id_info}', 'PlanificacionController@update')->name('planificacion_update');
		Route::post('/updategeometry', 'PlanificacionController@updateGeometry')->name('planificacion_update_geom');
		Route::post('/baja', 'PlanificacionController@baja')->name('planificacion_baja');
		Route::get('/testing', 'PlanificacionController@test');
	});
});

Route::group(['middleware' => ['web', 'auth', 'role:developer|area|administracion', 'checkArea:Arbolado,Dpto Técnico']], function() {
	Route::get('/formproblemas', 'FormProblemasController@Index')->name('formproblemas');
	Route::group(['prefix' => 'formproblemas'], function(){
		Route::get('/tabla', ['uses' => 'FormProblemasController@IndexTabla']);
		Route::post('/post', ['uses' => 'FormProblemasController@guardar']);
		Route::post('/tabla/post', ['uses' => 'FormProblemasController@Intervenir']);
	});
});
//MAPA TEMATICO
Route::get('/visualizador', 'VisualizadorController@Index')->name('visualizador');
Route::group(['prefix' => 'visualizador'], function(){
	Route::get('/puntosxid/{ids_puntos}', 'VisualizadorController@getPointsbyID');
	Route::get('/poligonosxid/{ids_poligonos}', 'VisualizadorController@getPoligonosbyID');
	Route::get('/trazasxid/{ids_trazas}', 'VisualizadorController@getTrazasbyID');
	Route::get('/exportar-datos', 'VisualizadorController@exportarDatos');
});

//DASHBOARD
Route::group(['middleware' => ['web', 'auth', 'role:developer|area|administracion']], function() {
	Route::get('/dashboard', 'DashboardController@Index');
	Route::get('/dashboard/datosindex', ['uses' => 'DashboardController@getDatosIndex']);
});

//SERVICIOS SUA
Route::group(['middleware' => ['web', 'auth', 'role:developer'],], function() {
	Route::get('/serviciosua', 'ServiciosSuaController@Index')->name('serviciosua');
	Route::group(['prefix' => 'serviciosua'], function(){
		Route::get('/intervenciones', 'ServiciosSuaController@IndexIntervenciones')->name('serviciosua_intervenciones');
		Route::get('/asignaciones', 'ServiciosSuaController@IndexAsignaciones')->name('serviciosua_asignaciones');
		Route::get('/resoluciones', 'ServiciosSuaController@IndexResoluciones')->name('serviciosua_resoluciones');
		Route::get('/cargarids', 'ServiciosSuaController@IndexCargarIDs')->name('serviciosua_cargarids');
		Route::post('/procesarids', 'ServiciosSuaController@procesarIds')->name('serviciosua_procesarids');
	});
	Route::get('/ajax/getidsolicitudes/{nro}/{anio}', 	['uses' => 'AjaxController@getIdSolicitudes']);
});

Route::group(['middleware' => ['web', 'auth', 'role:developer|administracion']], function() {
	Route::group(['prefix' => 'admin'], function(){

		Route::get('/trythis', 'AdministradorController@datos_complementarios_masive');

		Route::get('/dashboard', 'AdministradorController@Index')->name('dashboard');

		Route::get('/usuarios', 'AdministradorController@GestionarUsuarios')->name('usuarios');
		
		Route::group(['prefix' => 'usuarios'], function(){
			Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditView'])->name('usuarios_editar_id');
			Route::post('/editar', ['uses' => 'AdministradorController@EditUpdate']);
			Route::get('/registrar', ['uses' => 'AdministradorController@NewUserView'])->name('usuarios_registrar');
			Route::post('/nuevo', ['uses' => 'AdministradorController@NewUserCreate']);
			Route::post('/delete', ['uses' => 'AdministradorController@DeleteUser']);
		});

		Route::get('/datoscomplementarios', ['uses' => 'AdministradorController@DatosView'])->name('datoscomplementarios');

		Route::group(['prefix' => 'datoscomplementarios'], function(){
			Route::get('/nuevo', ['uses' => 'AdministradorController@NewDatosView'])->name('datoscomplementarios_nuevo');
			Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewDato']);
		});

		Route::get('/etiquetas', ['uses' => 'AdministradorController@GestionarEtiquetas'])->name('etiquetas');
		Route::group(['prefix' => 'etiquetas'], function(){
			Route::post('/delete', ['uses' => 'AdministradorController@DeleteEtiqueta']);
			Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditViewEtiquetas'])->name('etiquetas_editar_id');
			Route::post('/editar', ['uses' => 'AdministradorController@EditUpdateEtiquetas']);
			Route::get('/nueva', ['uses' => 'AdministradorController@NewEtiquetaView'])->name('etiquetas_nuevo');
			Route::post('/nueva/post', ['uses' => 'AdministradorController@NewEtiquetaCreate']);
		});

		Route::get('/estilos', ['uses' => 'AdministradorController@GestionarEstilos'])->name('estilos');
		Route::group(['prefix' => 'estilos'], function(){
			Route::get('/estilos/edit/{id}', ['uses' => 'AdministradorController@EditViewEstilos'])->name('estilos_editar_id');
			Route::post('/estilos/editar', ['uses' => 'AdministradorController@EditUpdateEstilos']);
		});
		
		Route::get('/areasconfig', ['uses' => 'AdministradorController@GestionarAreasConfig'])->name('areasconfig');
		Route::group(['prefix' => 'areasconfig'], function(){
			Route::get('/nuevo', ['uses' => 'AdministradorController@NewAreaView'])->name('areasconfig_nuevo');
			Route::get('/editar/{id}', ['uses' => 'AdministradorController@EditAreaView'])->name('areasconfig_editar_id');
			Route::post('/editar/post', ['uses' => 'AdministradorController@EditAreaUpdate'])->name('areasconfig_editar_post');
			Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewAreaConfig'])->name('areasconfig_post');
			Route::post('/delete', ['uses' => 'AdministradorController@DeleteArea'])->name('areasconfig_delete');
		});

		Route::get('/capasutiles', ['uses' => 'AdministradorController@GestionarCapasUtiles'])->name('capasutiles');
		Route::group(['prefix' => 'capasutiles'], function(){
			Route::get('/nuevo', ['uses' => 'AdministradorController@NewCapaView'])->name('capasutiles_nuevo');
			Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewCapa']);
			Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditCapaView'])->name('capasutiles_editar_id');
			Route::post('/editar', ['uses' => 'AdministradorController@EditCapaUpdate']);
		});

		Route::get('/importar', ['uses' => 'AdministradorController@Importar'])->name('importar');
	});
});

//RESPUESTAS AJAX JSON/ARRAY
Route::group(['prefix' => 'ajax'], function(){
	Route::get('/geojson', ['uses' => 'AjaxController@getGeojson']);
	Route::get('/planificacionpoints/{area}', ['uses' => 'AjaxController@getPlanificacionPoints']);
	Route::get('/planificacionpolygons/{area}', ['uses' => 'AjaxController@getPlanificacionPolygons']);
	Route::get('/planificacionpolylines/{area}', ['uses' => 'AjaxController@getPlanificacionPolylines']);
	Route::get('/puntosdehoy', ['uses' => 'AjaxController@getPuntosdeHoy']);
	Route::get('/poligonosdehoy', ['uses' => 'AjaxController@getPoligonosdeHoy']);
	Route::get('/lineasdehoy', ['uses' => 'AjaxController@getLineasdeHoy']);
	Route::get('/tags', ['uses' => 'AjaxController@getTags'])->name('ajax_tags');
	Route::get('/tags-grupo/{filtro}', ['uses' => 'AjaxController@getTagsByGroup']);
	Route::get('/grupos', ['uses' => 'AjaxController@getGrupos']);
	Route::get('/roles', ['uses' => 'AjaxController@getRoles'])->name('ajax_roles');
	Route::get('/cache', ['uses' => 'AjaxController@getCache']);
	Route::get('/legend', ['uses' => 'AjaxController@getLegend']);
	Route::get('/capasutiles', ['uses' => 'AjaxController@getCapasUtiles']);

	Route::get('/estilo_capa', ['uses' => 'AjaxController@getEstilosCapas'])->name('get_estilo_capa');
	Route::get('/formtable', ['uses' => 'AjaxController@getDatosFormTable']);
	Route::get('/datainter', ['uses' => 'AjaxController@getDatosIntervenciones']);


	Route::post('/puntos', ['uses' => 'AjaxController@getPuntos'])->name('get_puntos');
	Route::post('/poligonos', ['uses' => 'AjaxController@getPoligonos'])->name('get_poligonos');
	Route::post('/lineas', ['uses' => 'AjaxController@getLineas'])->name('get_lineas');

	Route::get('/visualizadordetails/{id}', ['uses' => 'AjaxController@getDetailsGeometries']);
	Route::get('/datos_complementarios_desclarga', 'AjaxController@getDatosDescLarga')->name('get_datoscomplementarios');

	//SERVICIOSUA
	Route::group(['prefix' => 'serviciosua'], function(){
		Route::get('/token', ['uses' => 'ServiciosSuaController@getToken']);
		Route::get('/id_intervenciones/', ['uses' => 'ServiciosSuaController@getIdIntervenciones']);
		Route::get('/id_asignaciones/', ['uses' => 'ServiciosSuaController@getIdAsignaciones']);
		Route::post('/asignar', ['uses' => 'ServiciosSuaController@Asignar']);
		Route::post('/intervenir', ['uses' => 'ServiciosSuaController@Intervenir']);
		Route::post('/resolver', ['uses' => 'ServiciosSuaController@Resolver']);
	});
});
//Datatables
Route::group(['prefix' => 'datatables'], function(){
	Route::get('/geometrias', ['uses' => 'DatatablesController@Geometrias'])->name('datatables_geometrias');
	Route::get('/geometrias-planificacion', ['uses' => 'DatatablesController@GeometriasPlanificacion'])->name('datatables_geometrias-planificacion');
	Route::get('/usuarios', ['uses' => 'DatatablesController@Usuarios'])->name('datatables_usuarios');
	Route::get('/etiquetas', ['uses' => 'DatatablesController@Etiquetas'])->name('datatables_etiquetas');
	Route::get('/estilos', ['uses' => 'DatatablesController@Estilos'])->name('datatables_estilos');
	Route::get('/areasconfig', ['uses' => 'DatatablesController@AreasConfig'])->name('datatables_areasconfig');
	Route::get('/equipo', ['uses' => 'DatatablesController@Equipo'])->name('datatables_equipo');
	Route::get('/capasutiles', ['uses' => 'DatatablesController@CapasUtiles'])->name('datatables_capasutiles');
	Route::get('/dash', ['uses' => 'DatatablesController@getInfo'])->name('datatables_dash');
	Route::get('/dashgraph', ['uses' => 'DatatablesController@getInfoForGraph'])->name('datatables_dashgraph');
	Route::get('/tablaArea', ['uses' => 'DatatablesController@tablaArea'])->name('datatables_tablaArea');
	Route::get('/datoscomplementarios/{id}', ['uses' => 'DatatablesController@DatosComplementarios'])->name('datatables_datoscomplementarios');
	Route::get('/datoscomplementariosall', ['uses' => 'DatatablesController@DatosComplementariosAll'])->name('datatables_datoscomplementarios_all');
});
//prueba pdf
Route::post('/pdf', ['uses' => 'PdfController@invoice']);

Route::get('/admin/calendarValidation', ['uses' => 'AdministradorController@calendarValidation']);
Route::get('/admin/areaTable',['uses' => 'AdministradorController@areaTable']);
Route::get('/datatables/test', ['uses' => 'DatatablesController@getAreasPorDia']);
Route::get('/graphs/interv',['uses' => 'DatatablesController@getIntervData']);
Route::get('/visualizador/getgg', ['uses' => 'DatatablesController@makeGg']);
