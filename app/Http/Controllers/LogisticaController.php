<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use App\AreaConfig;
use App\PlanificacionInfo;

class LogisticaController extends Controller
{
    public function Index()
    {
        return view('logistica');
    }   

    public function create(Request $request)
    {
    	$area = AreaConfig::findOrFail($request->area);

        $planificacion_info = PlanificacionInfo::find($request->id_seleccionados);

        return view('OrdenServicio.create')->with("area", $area)->with("planificacion_info", $planificacion_info);
    }

    public function listarPlanificacion(Request $request)
	{
        $today = \Carbon\Carbon::now()->format('Y-m-d').'%';

        $geometrias = DB::table('planificacion_info')
            ->join('tags as area', 'area.id_tag', '=', 'planificacion_info.id_area')
            ->join('tags as corte_calzada', 'corte_calzada.id_tag', '=', 'planificacion_info.id_corte_calzada')
            ->join('tags as tipo_trabajo', 'tipo_trabajo.id_tag', '=', 'planificacion_info.id_tipo_trabajo')
            ->where('planificacion_info.estado', '=', 'true')
            ->select(['planificacion_info.id_info', 'planificacion_info.id_area', 'planificacion_info.descripcion', 'planificacion_info.id_tipo_trabajo', 'planificacion_info.horario', 'planificacion_info.callezona', 'planificacion_info.id_corte_calzada', 'planificacion_info.tipo_geometria', 'planificacion_info.fecha_planificada', 'planificacion_info.id_usuario', 'area.desc AS area', 'corte_calzada.desc as corte_calzada', 'tipo_trabajo.desc as tipo_trabajo']);

        $datatables = app('datatables')->of($geometrias)
            ->setRowId('id_info')
            ->addColumn('action', function ($geometrias) {
                return '<a href="#" class="btn btn-xs btn-primary ubicar" data-id="'.$geometrias->id_info.'"><i class="glyphicon glyphicon-globe"></i></a>';
            });

        // additional column
        if ($page = $datatables->request->get('page')) {
            $datatables->addColumn('orden', function ($geometrias) {
                return '';
            });
        }

        // additional search
        if ($min = $datatables->request->get('min')) {
            $max = $datatables->request->get('max');
            $datatables->whereRaw("\"fecha_planificada\" BETWEEN '$min' AND '$max'");
        }

        if ($area = $datatables->request->get('area')) {
            $datatables->whereRaw("\"id_area\" = '$area'");
        }

        if ($idseleccionados = $datatables->request->get('idseleccionados')) {
            $datatables->whereRaw("\"id_info\" IN '$idseleccionados'");
        }

        if ($tipo_trabajo = $datatables->request->get('tipo_trabajo')) {
            $datatables->whereRaw("\"id_tipo_trabajo\" = '$tipo_trabajo'");
        }

        if ($corte_calzada = $datatables->request->get('corte_calzada')) {
            $datatables->whereRaw("\"id_corte_calzada\" = '$corte_calzada'");
        }
        if ($descripcion = $datatables->request->get('descripcion')) {
            $datatables->whereRaw("UPPER(\"descripcion\") LIKE UPPER('%$descripcion%')");
        }

        if ($calle_zona = $datatables->request->get('calle_zona')) {
            $datatables->whereRaw("UPPER(\"callezona\") LIKE UPPER('%$calle_zona%')");
        }

        return $datatables->make(true);
	}   
}
