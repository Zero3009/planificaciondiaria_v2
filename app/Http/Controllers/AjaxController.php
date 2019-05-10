<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Response;
use Cache;
use App\PlanificacionInfo;
use App\Intervenciones;
use App\ProblemasForm;
use App\DatosComplementarios;

class AjaxController extends Controller
{
    public function getGeojson() {
    	$today = \Carbon\Carbon::now()->format('Y-m-d').'%';
    	$calles=DB::table('geojson')
    		->where('created_at', 'like', '$today')
			->select('geojson', 'id_geojson')
			->get();
    	return Response::json($calles);
    }

    public function getPuntos(Request $request) {
        $addlineas = null;
        $select = null;

        if($request->fecha){
            if($request->fecha_hasta){
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha_hasta.'%';
            }
            else{
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha.'%';
            }
            $addlineas .= "AND lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59'";
        }
        else if ($request->fecha_planificada){
            $fecha = $request->fecha_planificada.'%';
            $fecha_hasta = $request->fecha_planificada.'%';
            $addlineas .= " AND (lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59' OR lg.fecha_planificada is NULL)";
            $addlineas .= " AND lg.estado = true";
        }

        if($request->area != "all" && $request->rol == "area"){
            $addlineas .= " AND lg.id_area = ".$request->area." ";
        }

        if($request->ids_info){
            $addlineas .= "AND lg.id_info IN (".$request->ids_info.") ";
        }

        if($request->origen == "visualizador"){
            $select .= "SELECT planificacion_info.id_info, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.datos_complementarios,planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.estado, planificacion_info.horario, planificacion_info.callezona, planificacion_info.id_corte_calzada, corte_calzada.desc as corte_calzada, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as created_at";
        }
        else{
            $select .= "SELECT planificacion_info.id_info, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.horario, planificacion_info.callezona, planificacion_info.id_corte_calzada, corte_calzada.desc as corte_calzada, planificacion_info.estado, planificacion_info.tipo_geometria, planificacion_info.id_usuario, planificacion_info.created_at, planificacion_info.updated_at, planificacion_info.datos_complementarios, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as fecha_planificada";
        }

        $query = DB::select("SELECT row_to_json(fc)
            FROM (
                SELECT 'FeatureCollection' AS type
                    ,array_to_json(array_agg(f)) AS features
                FROM (
                   SELECT 'Feature' AS type
                        , ST_AsGeoJSON(puntos.geom, 5)::json AS geometry
                        , row_to_json(lp) AS properties
                    FROM planificacion_info AS lg
                    INNER JOIN (
                        ".$select."                
                        FROM planificacion_info
                        INNER JOIN tags AS area ON planificacion_info.id_area = area.id_tag
                        INNER JOIN tags AS tipo_trabajo ON planificacion_info.id_tipo_trabajo = tipo_trabajo.id_tag
                        INNER JOIN tags AS corte_calzada ON planificacion_info.id_corte_calzada = corte_calzada.id_tag
                        ) AS lp ON lg.id_info = lp.id_info  
                    INNER JOIN (
                        SELECT * FROM points
                        ) AS puntos ON puntos.id_info = lg.id_info   
                    WHERE lg.estado = 'true'
                    ".$addlineas."      
                    ) AS f
                ) AS fc");
        return Response::json($query);
    }

    public function getPoligonos(Request $request) {
        $addlineas = null;
        $select = null;

        if($request->fecha){
            if($request->fecha_hasta){
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha_hasta.'%';
            }
            else{
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha.'%';
            }
            $addlineas .= "AND lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59'";
        }
        else if ($request->fecha_planificada){
            $fecha = $request->fecha_planificada.'%';
            $fecha_hasta = $request->fecha_planificada.'%';
            $addlineas .= " AND (lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59' OR lg.fecha_planificada is NULL)";
            $addlineas .= " AND lg.estado = true";
        }

        if($request->area != "all" && $request->rol == "area"){
            $addlineas .= " AND lg.id_area = ".$request->area." ";
        }

        if($request->ids_info){
            $addlineas .= "AND lg.id_info IN (".$request->ids_info.") ";
        }

        if($request->origen == "visualizador"){
            $select .= "SELECT planificacion_info.id_info, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.horario, planificacion_info.datos_complementarios, planificacion_info.estado, planificacion_info.callezona, planificacion_info.id_corte_calzada, corte_calzada.desc as corte_calzada, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as created_at";
        }
        else{
            $select .= "SELECT planificacion_info.id_info, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.horario, planificacion_info.callezona, planificacion_info.id_corte_calzada, planificacion_info.datos_complementarios, corte_calzada.desc as corte_calzada, planificacion_info.estado, planificacion_info.tipo_geometria, planificacion_info.id_usuario, planificacion_info.created_at, planificacion_info.updated_at, planificacion_info.datos_complementarios, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as fecha_planificada";
        }

        $query = DB::select("SELECT row_to_json(fc)
            FROM (
                SELECT 'FeatureCollection' AS type
                    ,array_to_json(array_agg(f)) AS features
                FROM (
                   SELECT 'Feature' AS type
                        ,ST_AsGeoJSON(polygons.geom, 5)::json AS geometry
                        ,row_to_json(lp) AS properties
                    FROM planificacion_info AS lg
                    INNER JOIN (
                        ".$select."
                        FROM planificacion_info
                        INNER JOIN tags AS area ON planificacion_info.id_area = area.id_tag
                        INNER JOIN tags AS tipo_trabajo ON planificacion_info.id_tipo_trabajo = tipo_trabajo.id_tag
                        INNER JOIN tags AS corte_calzada ON planificacion_info.id_corte_calzada = corte_calzada.id_tag
                        ) AS lp ON lg.id_info = lp.id_info  
                    INNER JOIN (
                        SELECT * FROM polygons
                        ) AS polygons ON polygons.id_info = lg.id_info   
                    WHERE lg.estado = 'true'
                    ".$addlineas."    
                    ) AS f
                ) AS fc");
        return Response::json($query);
    }

    public function getLineas(Request $request) {
        $addlineas = null;
        $select = null;

        if($request->fecha){
            if($request->fecha_hasta){
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha_hasta.'%';
            }
            else{
                $fecha = $request->fecha.'%';
                $fecha_hasta = $request->fecha.'%';
            }
            $addlineas .= "AND lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59'";
        }
        else if ($request->fecha_planificada){
            $fecha = $request->fecha_planificada.'%';
            $fecha_hasta = $request->fecha_planificada.'%';
            $addlineas .= " AND (lg.fecha_planificada BETWEEN '$fecha 00:00:00' AND '$fecha_hasta 23:59:59' OR lg.fecha_planificada is NULL)";
            $addlineas .= " AND lg.estado = true";
        }

        if($request->area != "all" && $request->rol == "area"){
            $addlineas .= " AND lg.id_area = ".$request->area." ";
        }

        if($request->ids_info){
            $addlineas .= "AND lg.id_info IN (".$request->ids_info.") ";
        }

        if($request->origen == "visualizador"){
            $select .= "SELECT planificacion_info.id_info, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.datos_complementarios, planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.horario, planificacion_info.estado, planificacion_info.callezona, planificacion_info.id_corte_calzada, corte_calzada.desc as corte_calzada, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as created_at";
        }
        else{
            $select .= "SELECT planificacion_info.id_info, planificacion_info.datos_complementarios, planificacion_info.id_area, area.desc as area, planificacion_info.descripcion, planificacion_info.id_tipo_trabajo, tipo_trabajo.desc as tipo_trabajo, planificacion_info.horario, planificacion_info.callezona, planificacion_info.id_corte_calzada, corte_calzada.desc as corte_calzada, planificacion_info.estado, planificacion_info.tipo_geometria, planificacion_info.id_usuario, planificacion_info.created_at, planificacion_info.updated_at, to_char(planificacion_info.fecha_planificada,'dd/mm/yyyy') as fecha_planificada";
        }

        $query = DB::select("SELECT row_to_json(fc)
            FROM (
                SELECT 'FeatureCollection' AS type
                    ,array_to_json(array_agg(f)) AS features
                FROM (
                   SELECT 'Feature' AS type
                        ,ST_AsGeoJSON(polylines.geom, 5)::json AS geometry
                        ,row_to_json(lp) AS properties
                    FROM planificacion_info AS lg
                    INNER JOIN (
                        ".$select."                 
                        FROM planificacion_info
                        INNER JOIN tags AS area ON planificacion_info.id_area = area.id_tag
                        INNER JOIN tags AS tipo_trabajo ON planificacion_info.id_tipo_trabajo = tipo_trabajo.id_tag
                        INNER JOIN tags AS corte_calzada ON planificacion_info.id_corte_calzada = corte_calzada.id_tag
                        ) AS lp ON lg.id_info = lp.id_info  
                    INNER JOIN (
                        SELECT * FROM linestrings
                        ) AS polylines ON polylines.id_info = lg.id_info   
                    WHERE lg.estado = 'true'
                    ".$addlineas."   
                    ) AS f
                ) AS fc");
        return Response::json($query);
    }

    public function getTagsByGroup($filtro){
        $query=DB::table('tags')
            ->where('estado', '=', true )
            ->where('grupo', '=', $filtro )
            ->select('desc', 'grupo', 'id_tag' )
            ->orderBy('desc', 'asc')
            ->get();
        return Response::json($query);
    }

    public function getTags() {
        $query=DB::table('tags')
            ->where('estado', '=', true )
            ->select('desc', 'grupo', 'id_tag' )
            ->orderBy('desc', 'asc')
            ->get();
        return Response::json($query);
    }  

    public function getGrupos() {
        $query=DB::table('tags')
            ->where('estado', '=', true )
            ->select('grupo')
            ->distinct('grupo')
            ->get();
        return Response::json($query);
    } 

    public function getRoles(Request $request)
    {
        $term = $request->term ?: '';
        $tags = DB::table ('roles')
            ->where('name', 'like', $term.'%')
            ->select('name AS text', 'id AS id')
            ->get();
        return Response::json($tags);
    }

    public function getIdSolicitudes($nro, $anio) {
        $id = DB::table('id_solicitudes')
            ->where('nro', '=', $nro)
            ->where('anio', '=', $anio)
            ->select()
            ->first();

        if(empty($id)){
            return Response::json([
                'estado' => 'No apto',
                'nro' => $nro,
                'anio' => $anio
            ]);
        }

        return Response::json($id);
    }

    public function getLegend() {
        $query=DB::table('estilos')
            ->join('tags', 'tags.id_tag', '=', 'estilos.id_area')
            ->select('estilos.id_area', 'estilos.color', 'tags.desc')
            ->distinct('id')
            ->get();
        return Response::json($query);
    } 

    public function getCapasUtiles(){
        $query=DB::table('capas_utiles')
            ->select('*')
            ->where("estado", 1)
            ->get();
        return Response::json($query);
    }

    public function getEstilosCapas(){
        $query=DB::table('estilos')
            ->select('*')
            ->get();
        $array = [];
        foreach ($query as $clave => $valor) {
            $array[$valor->id_area] = $valor;
        }
        return Response::json($array);
    }

    public function getDatosFormTable()
    {
        $query= ProblemasForm::select('problemas_form.id','nro_form', DB::raw("to_char(fecha,'DD-MM-YYYY') AS fecha"),'nro_sua','anio_sua','direccion','coordenada_x','coordenada_y','especie','distancia_ref','uno_terceros','dos_cables','tres_no_coincide','cuatro_no_existe','cinco_transito', DB::raw("CONCAT(seis_vectores, CASE WHEN vect_otro_text IS NOT NULL THEN concat(': ',vect_otro_text) END)  As seis_vectores"),'problemas_form.siete_vecino_niega','problemas_form.observaciones', DB::raw('(select intervenciones.tipo_intervencion from intervenciones where problemas_form.id  =   intervenciones.form_id order by id desc limit 1)'), DB::raw('(select intervenciones.notificado from intervenciones where problemas_form.id  =   intervenciones.form_id order by id desc limit 1)'))->get();
        return $query;
    }
    
    public function getDatosIntervenciones(Request $request)
    {
        $query = Intervenciones::select(['id','tipo_intervencion','fecha','observaciones'])
            ->where('form_id','=',$request->id)
            ->get();
        return $query;
    }

    public function getDetailsGeometries($id)
    {
        $query = PlanificacionInfo::select('id_info','datos_complementarios')
            ->where('id_info','=',$id)->get()
            ;
        return $query[0]->datos_complementarios;
    }

    public function getDatosDescLarga()
    {
        $query = DatosComplementarios::select('desc_larga','desc_corta')
                                        ->groupBy('desc_larga', 'desc_corta')
                                        ->get();
        return Response::json($query);
    }
}
