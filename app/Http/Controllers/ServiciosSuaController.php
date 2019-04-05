<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Response;
use Exception;
use App\ServicioSua;

class ServiciosSuaController extends Controller
{
    public function Index()
    {
        return view('serviciosua');
    }

    public function IndexIntervenciones()
    {
        return view('serviciosua.intervenciones');
    }

    public function IndexAsignaciones()
    {
        return view('serviciosua.asignaciones');
    }

    public function IndexResoluciones()
    {
        return view('serviciosua.resoluciones');
    }

    public function IndexCargarIDs()
    {
        return view('serviciosua.cargarids');
    }

    public function procesarIds(Request $request)
    {
        DB::table('id_solicitudes')->truncate();
        for($i=0;$i<count($request->id);$i++)
        {
            DB::table('id_solicitudes')->insert(
                ['id' => $request->id[$i], 'nro' => $request->nro[$i], 'anio' => $request->anio[$i]]
            );
        }
        return view('serviciosua.cargarids');
    }

    public function getToken(Request $request){

        /*return json_encode("Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJtcmFtaXJlNyIsImNyZWF0ZWQiOjE1NTIxNDQxNDE5MjAsInJvbGVzIjpbeyJhdXRob3JpdHkiOiJST0xFX1NVQV9SRUNJQklSIn0seyJhdXRob3JpdHkiOiJST0xFX1NVQV9SRUdJU1RSQSJ9LHsiYXV0aG9yaXR5IjoiUk9MRV9TVUFfUkVTT0xWRVIifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VBX0VYUE9SVEFSX0dJUyJ9LHsiYXV0aG9yaXR5IjoiUk9MRV9TVUFfR0VTVElPTkEifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VBX0RFUklWQVIifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VBX0NBUkdBUiJ9XSwiZXhwIjoxNTUyNzQ4OTQxfQ.CJkpq19avbL16GRKeS-pT7DVh7dQegAJWnpQtKZX70UT8NFCcb1Pahbly5pRXktUteclmnAqAYCcF0Qm4N9CtQ");
        */
        $post = $request->all();
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        //return $post;

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://t-sua.rosario.gob.ar/sua-auth/auth/login',
            CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode(array(
                "username" => $post['username'],
                "password" => $post['password']
            ))
        ));

        //dd($curl);
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        //return response::json($resp);
        return utf8_encode($resp);
        
    }

    public function getIdIntervenciones(Request $request){
        $post = $request->all();
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://t-sua.rosario.gob.ar/sua-api/area/'.$post['id_area'].'/tiposintervencion',
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'X-Authorization: Bearer '.$post['token'].'',
            ),
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return utf8_encode($resp);
    }

    public function getIdAsignaciones(Request $request){
        $post = $request->all();
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://t-sua.rosario.gob.ar/sua-api/area/'.$post['id_area'].'/equipos',
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'X-Authorization: Bearer '.$post['token'].'',
            ),
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return utf8_encode($resp);
    }

    public function Intervenir(Request $request){
        $post = $request->all();

        $post['servicio_tipo'] = "intervenciones";

        if($post['id_solicitud'] == "No declarado"){
            $this->guardarLog($post);
            return "log";
        }

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://sua.rosario.gob.ar/sua-api/solicitudes/'.$post['id_solicitud'].'/intervenir',
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'X-Authorization: Bearer '.$post['token'].'',
            ),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode(array(
                "id_area"           => $post['id_area'],
                "fecha"             => $post['fecha_intervencion'].' 00:00:00',
                "tipo_intervencion" => $post['tipo_resolucion'],
                "image"             => "",
                "descripcion"       => $post['leyenda'],
                "fuente"            => 0
            ))
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        $this->guardarLog($post, $resp);

        return array("serv" => $resp, "id_tr" => $post['id_tr']);
    }

    public function Asignar(Request $request){
        //return $request->all();
        $post = $request->all();

        $post['servicio_tipo'] = "asignaciones";

        if($post['id_solicitud'] == "No declarado"){
            $this->guardarLog($post);
            return "log";
        }

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://t-sua.rosario.gob.ar/sua-api/solicitudes/'.$post['id_solicitud'].'/asignar/'.$post['id_area'],
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'X-Authorization: Bearer '.$post['token'].'',
            ),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode(array(
                "id_equipo"         => $post['id_equipo'],
                "fecha"             => $post['fecha_intervencion'].' 00:00:00',
                "observaciones"     => $post['leyenda']
                
            ))
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        $this->guardarLog($post, $resp);

        return array("serv" => $resp, "id_tr" => $post['id_tr']);
    }

    public function Resolver(Request $request){
        $post = $request->all();

        $post['servicio_tipo'] = "resoluciones";

        if($post['id_solicitud'] == "No declarado"){
            $this->guardarLog($post);
            return "log";
        }

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://sua.rosario.gob.ar/sua-api/solicitudes/'.$post['id_solicitud'].'/resolver',
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'X-Authorization: Bearer '.$post['token'].'',
            ),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode(array(
                "id_area"           => $post['id_area'],
                "fecha"             => $post['fecha_intervencion'].' 00:00:00',
                "tipo"              => $post['tipo_resolucion'],
                "image"             => "",
                "solucion"          => $post['leyenda'],
                "id_motivo_cierre"  => $post['id_motivo_cierre']
            ))
        ));

        $retorno = array(
            "id_area"           => $post['id_area'],
            "fecha"             => $post['fecha_intervencion'].' 00:00:00',
            "tipo"              => $post['tipo_resolucion'],
            "image"             => "",
            "solucion"          => $post['leyenda'],
            "id_motivo_cierre"  => $post['id_motivo_cierre']
        );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        $this->guardarLog($post, $resp);
        
        return array("serv" => $resp, "id_tr" => $post['id_tr'], "retorno" => $retorno);
    }

    protected function guardarLog ($post, $resp = null) {

        $store = new ServicioSua();
            if($post['id_solicitud'] != "No declarado"){
                $store->id_solicitud = $post['id_solicitud'];
            }
            $store->nro = $post['nro'];
            $store->anio = $post['anio'];
            $store->leyenda = $post['leyenda'];
            $store->fecha_intervencion = $post['fecha_intervencion'];
            $store->tipo_resolucion = $post['tipo_desc'];
            if($resp == "\"ok\""){
                $store->estado = "Ok";
                
            }else if ($resp == null){
                $store->estado = "No apto";
            }
            else{
                $store->estado = "Ver";
                $store->url_error = 'https://sua.rosario.gob.ar/sua-webapp/solicitud/ver.do?accion=ver&id='.$post['id_solicitud'].'&origen=busqueda';
            }
            $store->servicio_tipo = $post['servicio_tipo'];
        $store->save();

        return $store;
    }
}
