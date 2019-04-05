<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Validator;
use Exception;
use App\Intervenciones;
use App\ProblemasForm;

class FormProblemasController extends Controller
{
    Public function Index()
    {
    	return view('formproblemas.formproblemas_nuevo');
    }
    public function IndexTabla()
    {
    	return view('formproblemas.formproblemas_tabla');
    }
    Public function guardar(Request $request)
    {
    	DB::beginTransaction();
    	try
    	{
    		$this->validate($request, [
                'fecha'				=> 'required',
                'domicilio'			=> 'required',
                'especie'			=> 'required',
                'dist'				=> 'required',
                'cant'				=> 'required|integer'
            ]);

    		$post = $request->all();

            $newDate = date("Y-m-d", strtotime($post['fecha']));
            //return $post;
            $problemas = new ProblemasForm();
                	$problemas->nro_form = $post['acta'];
                	$problemas->fecha = $newDate;
                	$problemas->nro_sua = $post['suan'];
                	$problemas->anio_sua = $post['anio_sua'];
                	$problemas->direccion = $post['domicilio'];
                	$problemas->especie = $post['especie'];
                	$problemas->distancia_ref = $post['dist'];
                	$problemas->coordenada_x = $post['coor_x'];
                	$problemas->coordenada_y = $post['coor_y'];

                	$problemas->uno_terceros = $post['cod1_selected'] ?? null;
              	    if($request->selecteds)
              	    {
              	    	$problemas->dos_cables = implode(",", $post['selecteds']);
              	    }
              	    $problemas->tres_no_coincide = $post['especie_disref'] ?? null;
              	    $problemas->cuatro_no_existe = $post['v_direccion_cod_4'] ?? null;
                	$problemas->cinco_transito = $post['observaciones'] ?? null;
                	if($request->selectedsVects)
                	{
                		$problemas->seis_vectores = implode(",", $post['selectedsVects']);
                		$problemas->vect_otro_text = $post['otro_text'] ?? null;
                	}
                    if($request->cod7)
                    {
                        if($request->cod7camps_dir || $request->cod7camps_nom_vecino || $request->cod7camps_tel)
                        {
                            $string = "";
                            if($request->cod7camps_dir)
                            {
                                $string = $post['cod7camps_dir'];
                            }
                            if($request->cod7camps_nom_vecino)
                            {
                                if($string <> "")
                                {
                                    $string = $string . ',' . $post['cod7camps_nom_vecino'];
                                }
                                else
                                {
                                    $string = $post['cod7camps_nom_vecino'];
                                }
                            }

                            if($request->cod7camps_tel)
                            {
                                if($string <> "")
                                {
                                    $string = $string . ',' . $post['cod7camps_tel'];
                                }
                                else
                                {
                                    $string = $post['cod7camps_tel'];
                                }
                            }
                            $problemas->siete_vecino_niega = $string;
                        }
                        else
                        {
                            $problemas->siete_vecino_niega = "Vecino se niega";
                        }

                    }
                	$problemas->observaciones = $post['observ'] ?? null;
          	$problemas->save();
    	
    	    //Commit y redirect con success
            DB::commit();
            return response()->json([
                'status' => 'success',
                'msg'    => 'Exito'
            ]);
        }
        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            if($e->getCode() == 23505)
            {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Error, el formulario ya existe'
                ],400);    
            }

            return response()->json([
                'status' => 'error',
                'msg'    => 'Error ' . $e->getCode() . ': ' . $e->getMessage() . 'Contacte a informática'
            ],404);
        }
    }
    public function Intervenir(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $this->validate($request, [
                'fecha'       => 'required',
                'obs'         => 'required',
                'opSelect'    => 'required',
                'id'          => 'required'
            ]);

            $post = $request->all();

            //return $post;
            $intervencion = new Intervenciones();
                $intervencion->tipo_intervencion = $post['opSelect'] ?? null;
                $intervencion->form_id = $post['id'] ?? null;
                $intervencion->observaciones = $post['obs'] ?? null;
                $intervencion->fecha = $post['fecha'] ?? null;
                $intervencion->notificado = $post['notificado'] ?? false;
            $intervencion->save();
        
            //Commit y redirect con success
            DB::commit();
            return redirect('/formproblemas/tabla')->with('status', 'Intervención cargada correctamente!');
        }
        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return view('formproblemas.formproblemas_tabla')->with('error', 'Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }
}
