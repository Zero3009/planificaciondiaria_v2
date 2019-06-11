<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PlanificacionInfo;
use App\PlanificacionPolygon;
use App\PlanificacionPoint;
use App\PlanificacionPolyline;
use App\DatosComplementarios;
use App\Tag;
use DB;
use Validator;
use Exception;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class VisualizadorController extends Controller
{
    public function Index()
    {
		$datos_complementarios = DatosComplementarios::all();
    	return view('visualizador')->with("datos_complementarios", $datos_complementarios);
    }

    public function getDatatable(Request $request)
    {
        $today = \Carbon\Carbon::now()->format('Y-m-d').'%';

        //Queries
        $geometrias = DB::table('planificacion_info')
            ->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->where('planificacion_info.estado', '=', 'true')
            ->select(['planificacion_info.id_info', 'planificacion_info.id_area', 'planificacion_info.descripcion', 'planificacion_info.id_tipo_trabajo', 'planificacion_info.horario', 'planificacion_info.callezona', 'planificacion_info.id_corte_calzada', 'planificacion_info.tipo_geometria', 'planificacion_info.fecha_planificada', 'planificacion_info.id_usuario', 'area.desc AS area', 'corte_calzada.desc as corte_calzada', 'tipo_trabajo.desc as tipo_trabajo', 'planificacion_info.datos_complementarios', 'planificacion_info.created_at']);

        //Editar tabla
        $datatables = app('datatables')->of($geometrias)
            ->setRowId('id_info')
            ->editColumn('datos_complementarios', function($geometrias) {
                $json = json_decode($geometrias->datos_complementarios, true);
                $formato = "";
                if(is_array($json) || is_object($json)){
                    $i = 0;
                    foreach ($json as $key => $value) {
                        if($value["value"] != null){
                            $desc_larga = DatosComplementarios::select("desc_larga")
                                ->where("desc_corta", "=", $value["label"])
                                ->pluck("desc_larga")
                                ->first();
                            if ($i == 0) { 
                                $formato .= $desc_larga . ': ' . $value["value"];
                            } else {
                                $formato .= ' | '.$desc_larga . ': ' . $value["value"];
                            }
                            $i++;  
                        }  
                    }
                    return $formato;
                }
            })
            ->addColumn('action', function ($geometrias) {
                return '<a href="#" class="btn btn-xs btn-primary ubicar" data-id="'.$geometrias->id_info.'"><i class="glyphicon glyphicon-globe"></i></a><a href="#" class="btn btn-xs btn-info dats" data-id="'.$geometrias->id_info.'"><i class="glyphicon glyphicon-search"></i></a>';
            });

        //Aplicar filtros
        $planificaciones = $this->filtros($request, $geometrias)->get();

        return $datatables->make(true);
    }


    public function exportarExcel(Request $request){

        //Queries
        $planificaciones = PlanificacionInfo::select('planificacion_info.id_info', 'area.desc AS area', 'planificacion_info.descripcion', 'planificacion_info.callezona', 'planificacion_info.horario as franja_horaria',  'planificacion_info.tipo_geometria', 'corte_calzada.desc as corte_calzada', 'tipo_trabajo.desc as tipo_trabajo',  'users.name as usuario', 'planificacion_info.fecha_planificada', 'planificacion_info.updated_at as fecha_actualizacion', 'planificacion_info.datos_complementarios')
            ->selectRaw('ST_AsText(lineas.geom) as lineas, ST_AsText(puntos.geom) as puntos, ST_AsText(poligonos.geom) as poligonos')
        	->join('users', 'users.id', '=', 'planificacion_info.id_usuario')
        	->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->leftJoin('points as puntos', 'puntos.id_info', '=', 'planificacion_info.id_info')
            ->leftJoin('linestrings as lineas', 'lineas.id_info', '=', 'planificacion_info.id_info')
            ->leftJoin('polygons as poligonos', 'poligonos.id_info', '=', 'planificacion_info.id_info')
        	->where("planificacion_info.id_area", $request->area)
        	->where("planificacion_info.estado", 1)
        	->orderby('planificacion_info.id_info', 'desc');
        $datos_area = Tag::where('id_tag', $request->area)->with(['datos_complementarios'])->first();

        //Aplicar filtros
        $planificaciones = $this->filtros($request, $planificaciones)->get();

        //Mapear columnas
        foreach ($datos_area->datos_complementarios as $key => $value) {
            foreach ($planificaciones as $planificacion) {
                $planificacion->setAttribute($value["desc_corta"], "");
            }
        }
        foreach ($planificaciones as $planificacion) {
            if(isset($planificacion->datos_complementarios)){
                foreach ($planificacion->datos_complementarios as $key => $value) {
                    $label2 = $value["label"];
                    $value2 = $value["value"];
                    $planificacion->$label2 = $value2;
                }
            }
        }
        $planificaciones->makeHidden('datos_complementarios');

        //Generar archivo excel
        $date = \Carbon\Carbon::now();
        $date = $date->format('d-m-Y');
        $file = Excel::create('Reporte', function($excel) use ($planificaciones){
			$excel->sheet('reporte', function($sheet) use ($planificaciones){
                $data= json_decode( json_encode($planificaciones), true);
                $sheet->fromArray($data);
            });
		});

		$file = $file->string('xlsx');
		$response =  array(
			'name' => "Reporte área ".$planificaciones[0]->area." - ".$date,
			'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
		);

		return response()->json($response);
    }

    public function exportarGeojson(Request $request){

        //Queries
        $datos_area = Tag::where('id_tag', $request->area)->with(['datos_complementarios'])->first();
        $planificaciones = DB::table('planificacion_info')
            ->selectRaw("json_build_object(
                'type', 'FeatureCollection',
                'crs',  json_build_object(
                    'type',      'name', 
                    'properties', json_build_object(
                        'name', 'EPSG:4326'  
                    )
                ), 
                'features', json_agg(
                    json_build_object(
                        'type',       'Feature',
                        'id',         planificacion_info.id_info,
                        'geometry',   ST_AsGeoJSON(coalesce(puntos.geom, lineas.geom, poligonos.geom))::json,
                        'properties', json_build_object(
                            -- list of fields
                            'id', planificacion_info.id_info,
                            'area', area.desc,
                            'descripcion', planificacion_info.descripcion,
                            'calle/zona', planificacion_info.callezona,
                            'franja horaria', planificacion_info.horario,
                            'tipo geom', planificacion_info.tipo_geometria,
                            'corte de calzada', corte_calzada.desc,
                            'tipo de trabajo', tipo_trabajo.desc,
                            'usuario', users.name,
                            'fecha planificada', planificacion_info.fecha_planificada,
                            'fecha ult. modificacion', planificacion_info.updated_at,
                            'datos_complementarios', planificacion_info.datos_complementarios
                        )
                    )
                )
            )")
            ->join('users', 'users.id', '=', 'planificacion_info.id_usuario')
            ->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->leftJoin('points as puntos', 'puntos.id_info', '=', 'planificacion_info.id_info')
            ->leftJoin('linestrings as lineas', 'lineas.id_info', '=', 'planificacion_info.id_info')
            ->leftJoin('polygons as poligonos', 'poligonos.id_info', '=', 'planificacion_info.id_info')
            ->where("planificacion_info.estado", 1);

        //Aplicar filtros
        $planificaciones = $this->filtros($request, $planificaciones)->first();
        $featureCollection = json_decode($planificaciones->json_build_object, true);

        //Insertar cada dato complementarios habilitado como vacio en el array de properties
        foreach ($datos_area->datos_complementarios as $key => $value) {
            foreach ($featureCollection['features'] as $index => $feature) {
                $featureCollection['features'][$index]['properties'][$value["desc_corta"]] = "";

            }
            
        }
    
        //Mapear datos en la propiedad correspondiente
        foreach ($featureCollection['features'] as $index => $feature) {
            if(isset($feature['properties']['datos_complementarios'])){
               foreach ($feature['properties']['datos_complementarios'] as $key => $value) {
                    $label2 = $value["label"];
                    $value2 = $value["value"];
                    $featureCollection['features'][$index]['properties'][$label2] = $value2;
                }
            }
            unset($featureCollection['features'][$index]['properties']['datos_complementarios']);
        }

        return response()->json($featureCollection);
    }

    protected function filtros(Request $request, $query){

        // additional search
        if ($min = $request->min) {
            $max = $request->max;
            $query->whereRaw("\"fecha_planificada\" BETWEEN '$min' AND '$max'");
        }

        if ($area = $request->area) {
            $query->whereRaw("\"id_area\" = '$area'");
        }

        if ($tipo_trabajo = $request->tipo_trabajo) {
            $query->whereRaw("\"id_tipo_trabajo\" = '$tipo_trabajo'");
        }

        if ($corte_calzada = $request->corte_calzada) {
            $query->whereRaw("\"id_corte_calzada\" = '$corte_calzada'");
        }

        if ($descripcion = $request->descripcion) {
            $query->whereRaw("UPPER(\"descripcion\") LIKE UPPER('%$descripcion%')");
        }

        if ($calle_zona = $request->calle_zona) {
            $query->whereRaw("UPPER(\"callezona\") LIKE UPPER('%$calle_zona%')");
        } 

        if ($datos_complementarios = $request->datos_complementarios) {
            $array_form = array();
            $strArray = explode("&", $datos_complementarios);
            foreach ($strArray as $item) {
                $array = explode("=", $item);
                $array_form[$array[0]] = $array[1];
            }
            foreach ($array_form as $key => $value) {
                if($value != ""){
                    $query->whereJsonContains(DB::raw('lower("datos_complementarios"::text)'), [["value" => strtolower("$value"), "label" => "$key"]]);
                }
            }  
        }

        return $query;
    }
}
