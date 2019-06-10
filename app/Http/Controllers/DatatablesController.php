<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Datatables;
use DB;
use Response;
use App\PlanificacionInfo;
use App\User;
use App\Tag;
use App\Estilo;
use App\AreaConfig;
use App\EquipoArea;
use App\CapaUtil;
use App\DatosComplementarios;
use Maatwebsite\Excel\Facades\Excel;


class DatatablesController extends Controller
{
    public function Usuarios()
    {
        $query = User::select(['users.id', 'users.name', 'users.email', 'users_info.estado as estado', 'tags.desc as area'])
            ->join('users_info', 'users_info.id_user', '=', 'users.id')
            ->join('tags', 'tags.id_tag', '=', 'users_info.id_area');

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                if($query->estado == false){
                    return '';
                }
                else{
                    return '<a href="'.route('usuarios_editar_id',[$query->id]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="btn btn-xs btn-danger delete" data-id="'.$query->id.'"><i class="glyphicon glyphicon-trash"></i></a>';
                }
                
            });

        return $datatables->make(true);
    }

    public function Etiquetas()
    {
        $query = Tag::select(['id_tag', 'desc', 'grupo', 'estado'])
            ->groupBy('id_tag');

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                return '<a href="'.route('etiquetas_editar_id',[$query->id_tag]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a>';
            });

        return $datatables->make(true);
    }

    public function Equipo()
    {
        $query = EquipoArea::all();

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                return '<a href="'.route('etiquetas_editar_id',[$query->id_equipo]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a>';
            });

        return $datatables->make(true);
    }

    public function Estilos()
    {
        $query = Estilo::select(['estilos.id', 'estilos.descripcion', 'estilos.iconUrl', 'estilos.weight', 'estilos.opacity', 'estilos.color', 'estilos.dashArray', 'estilos.fillOpacity', 'estilos.fillColor', 'tags.desc'])
            ->join('tags', 'tags.id_tag', '=', 'estilos.id_area');

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                return '<a href="'.route('estilos_editar_id',[$query->id]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a>';
            });

        return $datatables->make(true);
    }

    public function AreasConfig(){

        $query = AreaConfig::select(['areas_config.id_area', 'area.desc as area', 'secretaria.desc as secretaria', 'direccion.desc as direccion', 'areas_config.campo_descripcion', 'areas_config.estado'])
            ->join('tags as area', 'area.id_tag', '=', 'areas_config.id_area')
            ->join('tags as secretaria', 'secretaria.id_tag', '=', 'areas_config.id_secretaria')
            ->join('tags as direccion', 'direccion.id_tag', '=', 'areas_config.id_direccion');

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                if($query->estado == false){
                    return '';
                }
                else {
                    return '<a href="'.route('areasconfig_editar_id',[$query->id_area]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="btn btn-xs btn-danger delete" data-id="'.$query->id_area.'"><i class="glyphicon glyphicon-trash"></i></a>';
                }
            });

        return $datatables->make(true);
    }

    public function CapasUtiles()
    {
        $query = CapaUtil::select('*');

        $datatables = app('datatables')->of($query)
            ->addColumn('action', function ($query) {
                return '<a href="'.route('capasutiles_editar_id',[$query->id]).'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a>';
            });

        return $datatables->make(true);
    }
    public function getInfo(Request $request){
        $fecha = $request->fecha;
        $areas = \DB::table('tags')
            ->leftJoin('planificacion_info', function($query) use($fecha){
                $query->on('planificacion_info.id_area', '=', 'tags.id_tag')
                ->where('tags.estado','=','true')
                ->where('planificacion_info.estado','=','true')
                ->where(DB::raw('planificacion_info.created_at::Date'), '=', $fecha);
            })
            ->where('tags.grupo','=','area')
            ->selectRaw("tags.id_tag as idtag, tags.desc as areas, COUNT(planificacion_info.id_area) as cantidad_intervenciones, SUM(CASE WHEN planificacion_info.tipo_geometria = 'Polygon' and planificacion_info.estado = true THEN 1 ELSE 0 END) as poligonos,
            SUM(CASE WHEN planificacion_info.tipo_geometria = 'LineString' and planificacion_info.estado = true THEN 1 ELSE 0 END) as lineas,
            SUM(CASE WHEN planificacion_info.tipo_geometria = 'Point' and planificacion_info.estado = true THEN 1 ELSE 0 END) as puntos,
            min(planificacion_info.created_at::time) as horaPrimera, max(planificacion_info.created_at::time) as horaUltima, CASE WHEN COUNT(planificacion_info.id_area) = 0 THEN 'NO' ELSE 'SI' END as cargo")
            ->groupBy('tags.desc', 'tags.id_tag');
        $datatables = app('datatables')->of($areas)
            ->addColumn('action', function ($areas) {
                if($areas->cantidad_intervenciones > 0){
                    return '<a href="#" class="btn btn-xs btn-primary ubicar" onclick="myFunction('.$areas->idtag.')"><i class="glyphicon glyphicon-folder-open"></i></a>';
                }
            });
        
        return $datatables->make(true);
    }
    public function tablaArea(Request $request){
        $area = $request->area;
        $fecha = $request->fecha;
        $query = \DB::table('planificacion_info')
            ->join('tags as area', 'area.id_tag','=', 'planificacion_info.id_area')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->where('planificacion_info.id_area','=', $area)
            ->where(DB::raw('planificacion_info.created_at::Date'), '=', $fecha)
            ->selectRaw("planificacion_info.descripcion as descripcion, to_char(planificacion_info.created_at,'HH24:MI') as hora, area.desc as area, tipo_trabajo.desc as tipo, planificacion_info.horario as horario, planificacion_info.callezona as callezona");
        $datatables = app('datatables')->of($query);
        return $datatables->make(true);
    }
    public function getInfoForGraph(Request $request){
        $fecha = $request->fecha;
        
        $areas = \DB::table('tags')->selectRaw("tags.desc as area, planificacion_info.created_at::Date, SUM(CASE WHEN planificacion_info.tipo_geometria = 'Polygon' and planificacion_info.estado = true THEN 1 ELSE 0 END) as poligonos,
            SUM(CASE WHEN planificacion_info.tipo_geometria = 'LineString' and planificacion_info.estado = true THEN 1 ELSE 0 END) as lineas,
            SUM(CASE WHEN planificacion_info.tipo_geometria = 'Point' and planificacion_info.estado = true THEN 1 ELSE 0 END) as puntos, COUNT(planificacion_info.id_area) as count")
            ->join('planificacion_info', 'planificacion_info.id_area', '=', 'tags.id_tag')
            ->where('tags.grupo', 'area')
            ->where('planificacion_info.estado','=','true')
            ->where('tags.estado','=','true')
            ->whereRaw("CAST(planificacion_info.created_at AS DATE) <= to_timestamp('$fecha', 'YYYY-MM-DD') and CAST(planificacion_info.created_at AS DATE) >= to_timestamp('$fecha', 'YYYY-MM-DD')::timestamp without time zone at time zone 'Etc/UTC' - interval '7 day' or CAST(planificacion_info.created_at AS DATE) = '$fecha'")
            ->groupBy(DB::raw('CAST(planificacion_info.created_at AS DATE), tags.desc'))
            ->orderBy('tags.desc')
            ->get();
        return json_encode($areas);
        
    }
    public function getAreasPorDia(Request $request){
        $fecha = $request->fecha;
        $areas = \DB::table('tags')->selectRaw('tags.desc as area, estilos.color')
            ->join('planificacion_info', 'planificacion_info.id_area', '=', 'tags.id_tag')
            ->join('estilos','estilos.id_area','=','tags.id_tag')
            ->where('tags.grupo', 'area')
            ->where('tags.estado', '=', 'true')
            ->where('planificacion_info.estado', '=', 'true')
            ->whereRaw("CAST(planificacion_info.created_at AS DATE) <= to_timestamp('$fecha', 'YYYY-MM-DD') and CAST(planificacion_info.created_at AS DATE) >= to_timestamp('$fecha', 'YYYY-MM-DD')::timestamp without time zone at time zone 'Etc/UTC' - interval '7 day' or CAST(planificacion_info.created_at AS DATE) = '$fecha'")
            ->groupBy('tags.desc','estilos.color')
            ->orderBy('tags.desc')
            ->get();
        return json_encode($areas);
    }
    public function getIntervData(Request $request){
        $fecha = $request->fecha;
        $var = \DB::table('planificacion_info')
                ->join('tags', 'planificacion_info.id_tipo_trabajo', '=', 'tags.id_tag')
                ->selectRaw('tags.desc as desc, COUNT(planificacion_info.id_tipo_trabajo) as cantidad, planificacion_info.created_at::Date as fecha')
                ->where('planificacion_info.estado', '=', 'true')
                ->whereRaw("CAST(planificacion_info.created_at AS DATE) <= to_timestamp('$fecha', 'YYYY-MM-DD') and CAST(planificacion_info.created_at AS DATE) >= to_timestamp('$fecha', 'YYYY-MM-DD')::timestamp without time zone at time zone 'Etc/UTC' - interval '7 day' or CAST(planificacion_info.created_at AS DATE) = '$fecha'")
                ->groupBy(DB::raw('CAST(planificacion_info.created_at AS DATE), tags.desc'))
                ->orderBy('tags.desc')
                ->get();
        return json_encode($var);
    }
    public function DatosComplementarios($id){
        $query = DatosComplementarios::all();
        $query2 = AreaConfig::findOrFail($id);
        $datatables = app('datatables')->of($query)->addColumn('action', function($query) use($query2){
            foreach ($query2->datos_complementarios as $key => $value) {
                if($value->pivot->dato_id == $query->id){
                    return '<label class="switch">
                            <input type="checkbox" name="check[]" checked onclick="handleClick(this);" id="'. $query->id .'" value="'.$query->id .'">
                            <span class="slider round"></span>
                            </label>';
                }             
            }
            return '<label class="switch">
                            <input type="checkbox" name="check[]" onclick="handleClick(this);" id='. $query->id .' value="">
                            <span class="slider round"></span>
                            </label>';
        });
        return $datatables->make(true);
    }
    public function DatosComplementariosAll()
    {
        $query = DatosComplementarios::select('datos_complementarios.desc_corta','datos_complementarios.desc_larga','datos_complementarios.html');
        $datatables = app('datatables')->of($query);
        return $datatables->make(true);
    }

    public function GeometriasPlanificacion(Request $request){

        $geometrias = DB::table('planificacion_info')
            ->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->select(['planificacion_info.id_info', 'planificacion_info.id_area', 'planificacion_info.descripcion', 'planificacion_info.id_tipo_trabajo', 'planificacion_info.horario', 'planificacion_info.callezona', 'planificacion_info.id_corte_calzada', 'planificacion_info.tipo_geometria', 'planificacion_info.fecha_planificada', 'planificacion_info.id_usuario', 'area.desc AS area', 'corte_calzada.desc as corte_calzada', 'tipo_trabajo.desc as tipo_trabajo', 'planificacion_info.datos_complementarios', 'planificacion_info.created_at']);

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
                return '<a href="#" class="btn btn-xs btn-primary ubicar" data-id="'.$geometrias->id_info.'"><i class="glyphicon glyphicon-globe"></i></a>';
            });

        if ($idseleccionados = $datatables->request->get('ids')) {
            $geometrias->whereIn('planificacion_info.id_info', $idseleccionados);
        } else {
            $geometrias->where('planificacion_info.id_info', 999999999);
        }

        return $datatables->make(true);
    }
}