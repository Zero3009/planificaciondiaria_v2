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
Route::group(['prefix' => 'testing'], function(){

	Route::auth();

	Route::group(['middleware' => ['auth', 'role:developer|area|administracion']], function() {
		Route::get('/planificacion', 'PlanificacionController@Index')->name('planificacion');
		Route::group(['prefix' => 'planificacion'], function(){
			Route::post('/guardar', ['uses' => 'PlanificacionController@store']);
			Route::post('/update/{id_info}', ['uses' => 'PlanificacionController@update']);
			Route::post('/updategeometry', ['uses' => 'PlanificacionController@updateGeometry']);
			Route::post('/baja', ['uses' => 'PlanificacionController@baja']);
		});
		//Route::post('/planificacion/poligonizar', ['uses' => 'PlanificacionController@Poligonizar']);
	});

	Route::group(['middleware' => ['auth', 'role:developer|area|administracion', 'checkArea:Arbolado,Dpto Técnico']], function() {
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
		Route::get('/puntosxid/{ids_puntos}', ['uses' => 'VisualizadorController@getPointsbyID']);
		Route::get('/poligonosxid/{ids_poligonos}', ['uses' => 'VisualizadorController@getPoligonosbyID']);
		Route::get('/trazasxid/{ids_trazas}', ['uses' => 'VisualizadorController@getTrazasbyID']);
	});
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
		Route::get('/serviciosua', 'ServiciosSuaController@Index')->name('serviciosua');
		Route::group(['prefix' => 'serviciosua'], function(){
			Route::get('/serviciosua/intervenciones', ['uses' => 'ServiciosSuaController@IndexIntervenciones']);
			Route::get('/serviciosua/asignaciones', ['uses' => 'ServiciosSuaController@IndexAsignaciones']);
			Route::get('/serviciosua/resoluciones', ['uses' => 'ServiciosSuaController@IndexResoluciones']);
			Route::get('/serviciosua/cargarids', ['uses' => 'ServiciosSuaController@IndexCargarIDs']);
			Route::post('/serviciosua/procesarids', ['uses' => 'ServiciosSuaController@procesarIds']);
		});
			Route::get('/ajax/getidsolicitudes/{nro}/{anio}', 	['uses' => 'AjaxController@getIdSolicitudes']);
	});

	Route::group(['middleware' => ['auth', 'role:developer|administracion']], function() {
		Route::group(['prefix' => 'admin'], function(){
			Route::get('/dashboard', 'AdministradorController@Index')->name('dashboard');

			Route::get('/usuarios', 'AdministradorController@GestionarUsuarios')->name('usuarios');
			
			Route::group(['prefix' => 'usuarios'], function(){
				Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditView']);
				Route::post('/editar', ['uses' => 'AdministradorController@EditUpdate']);
				Route::get('/registrar', ['uses' => 'AdministradorController@NewUserView']);
				Route::post('/nuevo', ['uses' => 'AdministradorController@NewUserCreate']);
				Route::post('/delete', ['uses' => 'AdministradorController@DeleteUser']);
			});

			Route::get('/datoscomplementarios', ['uses' => 'AdministradorController@DatosView'])->name('datoscomplementarios');

			Route::group(['prefix' => 'datoscomplementarios'], function(){
				Route::get('/nuevo', ['uses' => 'AdministradorController@NewDatosView']);
				Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewDato']);
			});

			Route::get('/etiquetas', ['uses' => 'AdministradorController@GestionarEtiquetas'])->name('etiquetas');
			Route::group(['prefix' => 'etiquetas'], function(){
				Route::post('/delete', ['uses' => 'AdministradorController@DeleteEtiqueta']);
				Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditViewEtiquetas']);
				Route::post('/editar', ['uses' => 'AdministradorController@EditUpdateEtiquetas']);
				Route::get('/nueva', ['uses' => 'AdministradorController@NewEtiquetaView']);
				Route::post('/nueva/post', ['uses' => 'AdministradorController@NewEtiquetaCreate']);
			});

			Route::get('/estilos', ['uses' => 'AdministradorController@GestionarEstilos'])->name('estilos');
			Route::group(['prefix' => 'estilos'], function(){
				Route::get('/estilos/edit/{id}', ['uses' => 'AdministradorController@EditViewEstilos']);
				Route::post('/estilos/editar', ['uses' => 'AdministradorController@EditUpdateEstilos']);
			});

			Route::get('/equipo', ['uses' => 'AdministradorController@GestionarEquipo'])->name('equipo');
			Route::group(['prefix' => 'equipo'], function(){
				Route::get('/equipo/nuevo', ['uses' => 'AdministradorController@NewEquipoView']);
				Route::get('/equipo/editar/{id}', ['uses' => 'AdministradorController@EditEquipoView']);
				Route::post('/equipo/editar/post', ['uses' => 'AdministradorController@EditEquipoUpdate']);
				Route::post('/equipo/nuevo/post', ['uses' => 'AdministradorController@NewEquipoConfig']);
				Route::post('/equipo/delete', ['uses' => 'AdministradorController@DeleteEquipo']);
			});
			
			Route::get('/areasconfig', ['uses' => 'AdministradorController@GestionarAreasConfig'])->name('areasconfig');
			Route::group(['prefix' => 'areasconfig'], function(){
				Route::get('/nuevo', ['uses' => 'AdministradorController@NewAreaView']);
				Route::get('/editar/{id}', ['uses' => 'AdministradorController@EditAreaView'])->name('areasconfig_editar_id');
				Route::post('/editar/post', ['uses' => 'AdministradorController@EditAreaUpdate'])->name('areasconfig_editar_post');
				Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewAreaConfig']);
				Route::post('/delete', ['uses' => 'AdministradorController@DeleteArea'])->name('areasconfig_delete');
			});

			Route::get('/capasutiles', ['uses' => 'AdministradorController@GestionarCapasUtiles'])->name('capasutiles');
			Route::group(['prefix' => 'capasutiles'], function(){
				Route::get('/nuevo', ['uses' => 'AdministradorController@NewCapaView']);
				Route::post('/nuevo/post', ['uses' => 'AdministradorController@NewCapa']);
				Route::get('/edit/{id}', ['uses' => 'AdministradorController@EditCapaView']);
				Route::post('/editar', ['uses' => 'AdministradorController@EditCapaUpdate']);
			});

			Route::get('/exportar', ['uses' => 'AdministradorController@Exportar'])->name('exportar');
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

		Route::get('/estilo_capa', ['uses' => 'AjaxController@getEstilosCapas']);
		Route::get('/formtable', ['uses' => 'AjaxController@getDatosFormTable']);
		Route::get('/datainter', ['uses' => 'AjaxController@getDatosIntervenciones']);


		Route::post('/puntos', ['uses' => 'AjaxController@getPuntos']);
		Route::post('/poligonos', ['uses' => 'AjaxController@getPoligonos']);
		Route::post('/lineas', ['uses' => 'AjaxController@getLineas']);

		Route::get('/visualizadordetails/{id}', ['uses' => 'AjaxController@getDetailsGeometries']);
		Route::get('/datos_complementarios_desclarga', ['uses' => 'AjaxController@getDatosDescLarga']);

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
});