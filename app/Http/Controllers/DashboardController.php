<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Response;

class DashboardController extends Controller
{
    public function Index()
    {
        return View::make('dashboard');
    }

    public function getDatosIndex() {

    	$calles = DB::select("select tipo_trabajo.desc, count(*) as cantidad
			FROM planificacion_info
			INNER JOIN tags AS tipo_trabajo ON planificacion_info.id_tipo_trabajo = tipo_trabajo.id_tag
			GROUP BY tipo_trabajo.desc");

    	return Response::json($calles);

    }
}
