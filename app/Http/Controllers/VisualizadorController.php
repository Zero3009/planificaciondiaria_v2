<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PlanificacionInfo;
use App\PlanificacionPolygon;
use App\PlanificacionPoint;
use App\PlanificacionPolyline;
use App\DatosComplementarios;

use DB;
use Validator;
use Exception;
use Auth;

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
}
