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


    public function exportarDatos(Request $request){

        $planificaciones = PlanificacionInfo::select('planificacion_info.id_info', 'area.desc AS area', 'planificacion_info.descripcion', 'planificacion_info.callezona', 'planificacion_info.horario as franja_horaria',  'planificacion_info.tipo_geometria', 'corte_calzada.desc as corte_calzada', 'tipo_trabajo.desc as tipo_trabajo',  'users.name as usuario', 'planificacion_info.fecha_planificada', 'planificacion_info.updated_at as fecha_actualizacion', 'planificacion_info.datos_complementarios')
        	->join('users', 'users.id', '=', 'planificacion_info.id_usuario')
        	->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
        	->where("planificacion_info.id_area", $request->area)
        	->where("planificacion_info.estado", 1)
        	->orderby('planificacion_info.id_info', 'desc');

        $datos_area = Tag::where('id_tag', $request->area)->with(['datos_complementarios'])->first();

        // additional search
        if ($min = $request->min) {
            $max = $request->max;
            $planificaciones->whereRaw("\"fecha_planificada\" BETWEEN '$min' AND '$max'");
        }

        if ($tipo_trabajo = $request->tipo_trabajo) {
            $planificaciones->whereRaw("\"id_tipo_trabajo\" = '$tipo_trabajo'");
        }

        if ($corte_calzada = $request->corte_calzada) {
            $planificaciones->whereRaw("\"id_corte_calzada\" = '$corte_calzada'");
        }
        if ($descripcion = $request->descripcion) {
            $planificaciones->whereRaw("UPPER(\"descripcion\") LIKE UPPER('%$descripcion%')");
        }

        if ($calle_zona = $request->calle_zona) {
            $planificaciones->whereRaw("UPPER(\"callezona\") LIKE UPPER('%$calle_zona%')");
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
                    $planificaciones->whereJsonContains(DB::raw('lower("datos_complementarios"::text)'), [["value" => strtolower("$value"), "label" => "$key"]]);
                }
            }  
        }

        $planificaciones = $planificaciones->get();

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
}
