<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PlanificacionInfo;
use App\PolygonModel;
use App\AreaConfig;
use App\PointModel;
use App\LineStringModel;
use App\Tag;
use App\UserInfo;
use Auth;
use View;
use DB;
use Cache;
use Validator;
use Exception;

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class PlanificacionController extends Controller
{

    public function Index()
    {
        $query = DB::table('users_info')
            ->where('id_user', '=', Auth::user()->id)
            ->join('tags', 'tags.id_tag', '=', 'users_info.id_area')
            ->select('users_info.id_area', 'tags.desc')
            ->first();

        $campos_personalizados = DB::table('areas_config')
            ->where('id_area', '=', $query->id_area)
            ->select('campo_descripcion')
            ->first();

        $datos_complementarios = AreaConfig::find($query->id_area)->datos_complementarios;

        $tags =DB::table('tags')
            ->where('estado', '=', true )
            ->where(function($q){
                $q->where('grupo', '=', 'tipo_trabajo')
                ->orWhere('grupo', '=', 'corte_calzada');
            })
            ->select('desc', 'grupo', 'id_tag' )
            ->orderBy('desc', 'asc')
        ->get();

        return View::make('planificacion')->with('query', $query)->with('campos_personalizados', $campos_personalizados)->with('datos_complementarios', $datos_complementarios)->with('tags', $tags);
    }

    public function store(Request $request){
        DB::beginTransaction();

        $post = $request->all();


        try 
        {
            //Validaciones
            $validator = Validator::make($request->all(), [
                'geometrias'        => 'required',
                'tipo_geometria'    => 'required|max:20',
                'callezona'         => 'required|max:255',
                'id_area'           => 'required|integer',
                'id_tipo_trabajo'   => 'required|integer',
                'horario'           => 'required|max:20',
                'id_corte_calzada'  => 'required|integer',
                'id_usuario'        => 'required|integer',
                'fecha_planificada' => 'date|nullable',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 401);
            }

            //Funcion reutilizar el formato de datos
            $datos_formateados = $this->formatDatosComplementarios($post['datos_complementarios']);
            $contador = [];
            //Definir numero de estilo dependiendo del area

            $getarea = Tag::find($post['id_area']);

            $relacion = $getarea->estilo;

            $estilos = $relacion->id;

            $tipo_geometria = $post['tipo_geometria'];
            $array_geometry  = $post['geometrias'];


            $array = $post['descripcion'];
            if (isset($array) && is_array($array))
            {
                $desc_personalizada = implode(",",$array);
            }
            else
            {    
                $desc_personalizada = "";
            }
            if($post['fecha_planificada'] == ""){    
                $post['fecha_planificada'] = null;
            }
            //return $datos_formateados;
            //Store informacion general
            $info = new PlanificacionInfo();
                $info->id_area = $post['id_area'];
                $info->callezona = $post['callezona'];
                $info->descripcion = $desc_personalizada;
                $info->id_tipo_trabajo = $post['id_tipo_trabajo'];
                $info->horario = $post['horario'];
                $info->id_corte_calzada = $post['id_corte_calzada'];
                $info->tipo_geometria = $post['tipo_geometria'];
                $info->id_usuario = $post['id_usuario'];
                $info->fecha_planificada = $post['fecha_planificada'];
                $info->datos_complementarios = $datos_formateados ?? null;
            $info->save();  

            //Guardar dependiento el tipo de geometria en las diferentes tablas
            if($tipo_geometria == 'Point'){
                $point = new PointModel();
                    $point->id_info = $info->id_info;
                    $point->geom = new Point($array_geometry[1],$array_geometry[0]);
                    $point->estilo = $estilos;
                $point->save(); 
            }

            elseif ($tipo_geometria == 'Polygon') {
                $geometria = array();
                for($a=0;$a<count($array_geometry[0]);$a++){
                    $puntos = new Point($array_geometry[0][$a][1], $array_geometry[0][$a][0]);
                    array_push($geometria, $puntos);
                }
                $collection = new LineString($geometria);
                $polygon = new PolygonModel();
                    $polygon->id_info = $info->id_info;
                    $polygon->geom = new Polygon([$collection]);
                    $polygon->estilo = $estilos;
                $polygon->save();   
            }

            elseif ($tipo_geometria == 'LineString'){
                $geometria = array();
                for($a=0;$a<count($array_geometry);$a++){
                    $puntos = new Point($array_geometry[$a][1], $array_geometry[$a][0]);
                    array_push($geometria, $puntos);
                }
                $polyline = new LineStringModel();
                    $polyline->id_info = $info->id_info;
                    $polyline->geom = new LineString($geometria);
                    $polyline->estilo = $estilos;
                $polyline->save();
            }

            //Commit y redirect con success
            DB::commit();
            return response(['msg' => 'Se cargo correctamente la geometria', 'id_tr' => $post['id_tr'] ?? null], 200);
        }
        catch (Exception $e)
        {
            return $post['id_tr'];
            //Rollback y redirect con error
            DB::rollback();
            return response(['msg' => 'Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática', 'id_tr' => $post['id_tr'] ?? null], 401);
        }
    }
    public function update(Request $request, $id_info){
        DB::beginTransaction();

        $post = $request->all();

        try 
        {
            //Validaciones
            $validator = Validator::make($request->all(), [
                'callezona'         => 'required|max:60',
                'descripcion'       => 'required|max:60',
                'id_tipo_trabajo'   => 'required|integer',
                'horario'           => 'required|max:20',
                'id_corte_calzada'  => 'required|integer',
                'id_usuario'        => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 401);
            }

            //Funcion reutilizar el formato de datos
            if($post['datos_complementarios'] != null){
                $datos_formateados = $this->formatDatosComplementarios($post['datos_complementarios']);
            }

            $array = $post['descripcion'];

            if (isset($array)){
                $desc_personalizada = implode(",",$array);
            }
            else{    
                $desc_personalizada = "";
            }
            if($post['fecha_planificada'] == ""){    
                $post['fecha_planificada'] = null;
            }

            $info = PlanificacionInfo::find($id_info);
                $info->callezona = $post['callezona'];
                $info->descripcion = $desc_personalizada;
                $info->id_tipo_trabajo = $post['id_tipo_trabajo'];
                $info->horario = $post['horario'];
                $info->id_corte_calzada = $post['id_corte_calzada'];
                $info->id_usuario = $post['id_usuario'];
                $info->datos_complementarios = $datos_formateados ?? null;
                $info->fecha_planificada = $post['fecha_planificada'];
            $info->save();  

            //Commit y redirect con success
            DB::commit();
            return response(['msg' => 'Se cargo correctamente la geometria'], 200);
        }
        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return response(['msg' => 'Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática'], 401);
        }
    }

    public function updateGeometry(Request $request){
        DB::beginTransaction();
        try 
        {
            //Validaciones
            $validator = Validator::make($request->all(), [
                'id_info'           => 'required',
                'geometrias'        => 'required',
                'tipo_geometria'    => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 401);
            }

            $post = $request->all();

            $return = array();

            for($i=0;$i <count($post['tipo_geometria']);$i++)
            {
                $tipo_geometria = $post['tipo_geometria'][$i];
                $array_geometry  = $post['geometrias'][$i];
                $id_info  = $post['id_info'][$i];
                $direccion_nueva  = $post['direccion_new'][$i];

                if ($tipo_geometria == 'Polygon') {
                    $geometria = array();
                    for($a=0;$a<count($array_geometry[0]);$a++){
                        $puntos = new Point($array_geometry[0][$a][1], $array_geometry[0][$a][0]);
                        array_push($geometria, $puntos);
                    }
                    $collection = new LineString($geometria);
                    $polygon = PolygonModel::find($id_info);
                        $polygon->geom = new Polygon([$collection]);
                    $polygon->save();
                    array_push($return, $polygon);
                }
                elseif ($tipo_geometria == 'LineString'){
                    $geometria = array();
                    for($a=0;$a<count($array_geometry);$a++){
                        $puntos = new Point($array_geometry[$a][1], $array_geometry[$a][0]);
                        array_push($geometria, $puntos);
                    }
                    $polyline = LineStringModel::find($id_info);
                        $polyline->geom = new LineString($geometria);
                    $polyline->save();
                    array_push($return, $polyline);
                }
                elseif ($tipo_geometria == 'Point'){
                    $puntos = new Point($array_geometry[1], $array_geometry[0]);
                    $point = PointModel::find($id_info);
                        $point->geom = $puntos;
                    $point->save();
                    $info = PlanificacionInfo::find($id_info);
                        $info->callezona = $direccion_nueva;
                    $info->save();
                    array_push($return, $point." ".$info);
                }
            }

            //Commit y redirect con success
            DB::commit();
            return response(['msg' => $return], 200);
        }
        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return response(['msg' => 'Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática'], 401);
        }
    }

    public function baja(Request $request){

        $post = $request->all();
            $return = array();

            for($i=0;$i <count($post['id_info']);$i++)
            {
                $id_usuario  = $post['id_usuario'][$i];
                $id_info  = $post['id_info'][$i];

                $info = PlanificacionInfo::find($id_info);
                    $info->estado = false;
                    $info->id_usuario = $id_usuario;
                $info->save();
                array_push($return, $info); 
            }

        return $return;
    }   

    protected function formatearDatosComplementarios($datos){
        if($datos != ""){
            $dats = explode("&", $datos);
            $object = null;
            for ($iasd = 0;$iasd < sizeof($dats);$iasd++)
            {   
                $result = explode('=', $dats[$iasd]);
                if($object == null){
                    $object = (object) [$result[0] => urldecode($result[1]) ]; 
                }else{
                    $name = $result[0];
                    $object->$name = urldecode($result[1]);    
                }                
            }
            return json_encode($object);
        }
    }

    private function formatDatosComplementarios($datos)
    {
        $datos = $this->repair($datos);
        if($datos != "")
        {    
            $newArraySimple = [];
            foreach ($datos as $key => $value) {
                $newObject = new \stdClass();
                $newObject->label = urldecode($key);
                $newObject->value = urldecode($value);
                array_push($newArraySimple, $newObject);          
            }
            return $newArraySimple;
        }
    }

    private function repair($data)
    {
        $data = explode("&",$data);   
        $dataarray;
        for($i = 0;$i < sizeof($data);$i++)
        {
            $result = explode("=", $data[$i]);
            $dataarray[$result[0]] = $result[1];
        }
        return $dataarray;
    }

    public function test()
    {
        $test = PlanificacionInfo::select('datos_complementarios',DB::raw('json_array_elements()'))->where('id_info', '=',36935)->get();
        return $test[0]->datos_complementarios[0];
    }
}
