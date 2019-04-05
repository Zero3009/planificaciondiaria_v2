<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\UserInfo;
use App\Tag;
use App\Estilo;
use App\PlanificacionInfo;
use App\AreaConfig;
use App\EquipoArea;
use App\CapaUtil;
use App\DatosComplementarios;
use Redirect;
use DB;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdministradorController extends Controller
{
    
    public function Index()
    {   
        return view('administracion');
    }
    public function getInfo(){
        $areas = Tag::select(['id_tag as id_area', 'desc as desc_area'])->where('grupo', '=', 'area')->where('estado', '=', true)->get()->toArray();
  
        foreach ($areas as $key => $value) {
            $planif = PlanificacionInfo::where('id_area', '=', $value['id_area'])->where('fecha_planificada', '=', $fecha)->count();
            array_push($areas[$key], $planif);
        }
        $areas = $this->change_Key($areas,'0','cantidad_intervenciones');
        $datatables = app('datatables')->of($areas)
            ->addColumn('action', function ($areas) {
                if($query->estado == false){
                    return '';
                }
                else {
                return '<a href="/admin/areasconfig/editar/'.$query->id_area.'" class="btn btn-xs btn-primary details-control"><i class="glyphicon glyphicon-edit"></i></a><a href="#" class="btn btn-xs btn-danger delete" data-id="'.$query->id_area.'"><i class="glyphicon glyphicon-trash"></i></a>';
                }
            });
        echo "<script>console.log('sucess')</script>";
        return $datatables->make(true);
    }
    public function change_Key(array $plain, $keyOriginal, $keyNew){
        foreach($plain as &$tag){
            $tag["$keyNew"] = $tag["$keyOriginal"];
            unset($tag["$keyOriginal"]);
        }
        return $plain;
    }
    public function GestionarUsuarios()
    {
        return view('admin.usuarios');
    }

    public function GestionarEtiquetas()
    {
        return view('admin.etiquetas');
    }
    
    public function GestionarEquipo()
    {
        return view('admin.equipo');
    }

    public function GestionarEstilos()
    {
        return view('admin.estilos');
    }

    public function GestionarAreasConfig()
    {
        return view('admin.areasconfig');
    }

    public function GestionarCapasUtiles()
    {
        return view('admin.capasutiles');
    }

    public function NewCapaView()
    {
        return view('admin.capasutiles_nuevo');
    }

    public function Exportar()
    {
        return view('admin.exportar');
    }

    public function Importar()
    {
        return view('admin.importar');
    }

    public function EditView($id)
	{

		$user = User::select(['users.id', 'users.name', 'users.email', 'users.password', 'tags.desc as area', 'users_info.id_area'])
            ->where('users.id', '=', $id)
            ->join('users_info', 'users_info.id_user', '=', 'users.id')
            ->join('tags', 'tags.id_tag', '=', 'users_info.id_area')
            ->first();

        return View::make('admin.usuarios_editar')->with('user', $user);
	}

    public function EditCapaView($id)
    {

        $capaxid = CapaUtil::select('*')
            ->where('id', '=', $id)
            ->first();

        return View::make('admin.capasutiles_editar')->with('capaxid', $capaxid);
    }

	public function EditUpdate(Request $request)
	{

        DB::beginTransaction();
        
        try 
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'area' => 'required',
                'id_user' => 'required',
                'passNuevo' => 'required_with:passNuevoRep|same:passNuevoRep',
                'passNuevoRep' => 'required_with:passNuevo',
            ]);

            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $query = User::findOrFail($request->id_user);
                $query->name = $request->name;
                $query->email = $request->email;
                if($request->PassNuevo == $request->PassNuevoRep and $request->passNuevo<>"" and $request->passNuevoRep<>""){
                    $query->password = bcrypt($request->passNuevo);
                }
            $query->save();

            $query2 = UserInfo::findOrFail($request->id_user);
                $query2->id_area = $request->area;
            $query2->save();

            $prueba = array();

            //Eliminar roles
            foreach ($query->roles as $key => $value) {
                array_push($prueba, $value->id);
                if (!in_array($value->id, $request->roles)) {
                    $query->roles()->detach($value->id);
                }
            }

            //Agregar roles
            foreach ($request->roles as $key => $value) {
                if (!in_array($value, $prueba)) {
                    $query->roles()->attach($value);
                }
            }

            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/usuarios")
                ->with('status', 'Se ha editado correctamente el usuario.');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
	}

	public function NewUserView()
	{
        return view('admin.usuarios_nuevo');
	}

    public function newUserCreate(Request $request){

        DB::beginTransaction();
        
        try 
        {
           //Validaciones
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
                'area' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }


            $query = new User;
                $query->name = $request->name;
                $query->email = $request->email;
                $query->password = bcrypt($request->password);
            $query->save();

            UserInfo::create([
            'id_user' => $query->id,
            'id_area' => $request['area']
            ]);

            $query->roles()->attach($request->roles);

            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/usuarios")
                ->with('status', 'Usuario creado correctamente');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }

    public function DeleteUser(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = UserInfo::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();


        return Redirect::to('/admin/usuarios')->with('status', 'Se ha eliminado correctamente el usuario.');
    }

    public function DeleteEtiqueta(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $query = Tag::find($request['id']);

        $query->update([
            'estado' => false,
        ]);

        return Redirect::to('/admin/etiquetas')->with('status', 'Se ha dado de baja correctamente la etiqueta.');
    }

    public function EditViewEtiquetas($id)
    {
        $query = Tag::find($id);
        return View::make('admin.etiquetas_editar')->with('query', $query);
    }
    public function EditUpdateEtiquetas(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'grupo' => 'required',
            'id' => 'required',
        ]);

        $post = $request->all();

        Tag::find($post['id'])->update([
            'desc' => $post['nombre'],
            'grupo' => $post['grupo'],
            'estado' => $post['estado']
            ]);
        return Redirect::to('/admin/etiquetas')->with('status', 'Se ha editado correctamente la etiqueta.');
    }

    public function NewEtiquetaView()
    {
        return view('admin.etiquetas_nuevo');
    }
    public function newEtiquetaCreate(Request $request){

        $this->validate($request, [
            'desc' => 'required|max:60|unique:tags',
            'grupo' => 'required|max:60',
        ]);

        $tag = Tag::create([
            'desc' => $request['desc'],
            'grupo' => $request['grupo']
        ]);
        
        return Redirect::to('/admin/etiquetas')->with('status', 'Se ha creado la etiqueta correctamente.');
    }

    public function EditViewEstilos($id)
    {
        $query = Estilo::find($id);
        $query2 = Tag::find($query->id_area);
        return View::make('admin.estilos_editar')->with('query', $query)->with('query2', $query2);
    }

    public function EditUpdateEstilos(Request $request)
    {
        $this->validate($request, [
            'descripcion' => 'required',
            'iconurl' => 'required',
            'color' => 'required',
        ]);

        $post = $request->all();

        Estilo::find($post['id'])->update([
            'descripcion' => $post['descripcion'],
            'iconUrl' => $post['iconurl'],
            'weight' => $post['weight'],
            'opacity' => $post['opacity'],
            'color' => $post['color'],
            'dashArray' => $post['dasharray'],
            'fillOpacity' => $post['fillopacity'],
            'fillColor' => $post['fillcolor'],
        ]);
        return Redirect::to('/admin/estilos')->with('status', 'Se ha editado correctamente la etiqueta.');
    }

    public function NewAreaView (){
        return View::make('admin.areasconfig_nuevo');
    }
    public function EditAreaView ($id){

        $query = AreaConfig::select(['areas_config.id_area', 'areas_config.id_secretaria', 'areas_config.id_direccion', 'areas_config.campo_descripcion', 'area.desc as area', 'secretaria.desc as secretaria', 'direccion.desc as direccion'])
            ->where('areas_config.id_area', '=', $id)
            ->join('tags as area', 'areas_config.id_area', '=', 'area.id_tag')
            ->join('tags as secretaria', 'areas_config.id_secretaria', '=', 'secretaria.id_tag')
            ->join('tags as direccion', 'areas_config.id_direccion', '=', 'direccion.id_tag')
            ->first();

        $query2 = EquipoArea::all();

        return View::make('admin.areasconfig_editar')->with('areaconfig', $query)->with('equipos', $query2);
    }
    public function NewAreaConfig (Request $request){
        DB::beginTransaction();  
        try 
        {
           //Validaciones
            $validator = Validator::make($request->all(), [
                
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $query = new Tag;
                $query->desc = $request->area;
                $query->grupo = "area";
            $query->save();

            $array = $request->desc_personalizada;
            $desc_personalizada = implode(",",$array);


            $query2 = new AreaConfig;
                $query2->id_area = $query->id_tag;
                $query2->id_secretaria = $request->secretaria;
                $query2->id_direccion = $request->direccion;
                $query2->campo_descripcion = $desc_personalizada;
            $query2->save();

            Estilo::create([
                'descripcion' => "Default",
                'iconUrl' => "/plugins/leaflet/images/marker-icon-2x-black.png",
                'weight' => "8.00",
                'opacity' => "1.00",
                'color' => "#000000",
                'dashArray' => "15, 10, 5, 10",
                'fillOpacity' => "0.40",
                'fillColor' => "#000000",
                'id_area' => $query2->id_area
            ]);

            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/areasconfig")
                ->with('status', 'Area creada correctamente');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }

    public function EditAreaUpdate(Request $request){
        DB::beginTransaction();  
        try 
        {
            //return $request->all();
           //Validaciones
            $validator = Validator::make($request->all(), [
                
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }

           /* $query = Tag::find($request->id_area);
                $query->desc = $request->area;
            $query->save();*/


            $array = $request->desc_personalizada;
            if (isset($array)){
                $desc_personalizada = implode(",",$array);
            }
            else{    
                $desc_personalizada = "";
            }

            $query2 = AreaConfig::find($request->id_area);
                $query2->id_secretaria = $request->secretaria;
                $query2->id_direccion = $request->direccion;
                $query2->campo_descripcion = $desc_personalizada;
            $query2->save();
            $query2->datos_complementarios()->detach();
            $query2->datos_complementarios()->attach($request->check);
            $query2->equipo_area()->detach();
            $query2->equipo_area()->attach($request->equipos);
            
            /*
                $query2->html = $request->html;
                $query2->desc_corta = $request->desc_corta;
                $query2->desc_larga = $request->desc_larga;
            */
            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/areasconfig")
                ->with('status', 'Area modificada correctamente');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un error: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }

    public function DeleteArea(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = AreaConfig::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();


        return Redirect::to('/admin/areasconfig')->with('status', 'Se ha dado de baja el area correctamente.');
    }

    public function NewEquipoView (){
        return View::make('admin.equipo_nuevo');
    }
    public function EditEquipoView ($id){
        $query = AreaConfig::select(['areas_config.id_area', 'areas_config.id_secretaria', 'areas_config.id_direccion', 'areas_config.campo_descripcion', 'area.desc as area', 'secretaria.desc as secretaria', 'direccion.desc as direccion'])
            ->where('areas_config.id_area', '=', $id)
            ->join('tags as area', 'areas_config.id_area', '=', 'area.id_tag')
            ->join('tags as secretaria', 'areas_config.id_secretaria', '=', 'secretaria.id_tag')
            ->join('tags as direccion', 'areas_config.id_direccion', '=', 'direccion.id_tag')
            ->first();

        return View::make('admin.areasconfig_editar')->with('areaconfig', $query);
    }
    public function NewEquipoConfig (Request $request){
        DB::beginTransaction();  
        try 
        {
           //Validaciones
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $query = new EquipoArea;
                $query->descripcion = $request->descripcion;
            $query->save();

            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/equipo")
                ->with('status', 'Equipo creada correctamente');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un errro: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }

    public function EditEquipoUpdate(Request $request){
        DB::beginTransaction();  
        try 
        {
           //Validaciones
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withErrors($validator)
                            ->withInput();
            }

            $query = EquipoArea::find($request->id_area);
                $query->descripcion = $request->descripcion;
            $query->save();

            //Commit y redirect con success
            DB::commit();
            return redirect("/admin/equipo")
                ->with('status', 'Equipo modificado correctamente');
        }

        catch (Exception $e)
        {
            //Rollback y redirect con error
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors('Se ha producido un error: ( ' . $e->getCode() . ' ): ' . $e->getMessage().' - Copie este texto y envielo a informática');
        }
    }

    public function DeleteEquipo(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $queryinfo = EquipoArea::find($request['id']);
            $queryinfo->estado = false;
        $queryinfo->save();


        return Redirect::to('/admin/equipo')->with('status', 'Se ha dado de baja el area correctamente.');
    }

    public function NewCapa(Request $request)
    {
        $this->validate($request, [
            'nombre_capa' => 'required',
            'geojson' => 'required',
        ]);

        $post = $request->all();

        $query = new CapaUtil;
            $query->nombre = $post['nombre_capa'];
            $query->iconUrl = $post['iconurl'];
            $query->weight = $post['weight'];
            $query->opacity = $post['opacity'];
            $query->color = $post['color'];
            $query->dashArray = $post['dasharray'];
            $query->fillOpacity = $post['fillopacity'];
            $query->fillColor = $post['fillcolor'];
            $query->geojson = $post['geojson'];
        $query->save();

        return Redirect::to('/admin/capasutiles')->with('status', 'Se ha añadido correctamente la capa util.');
    }

    public function EditCapaUpdate(Request $request){
        $this->validate($request, [
            'nombre_capa' => 'required',
            'geojson' => 'required',
        ]);

        $post = $request->all();

        $query = CapaUtil::find($post['id']);
            $query->nombre = $post['nombre_capa'];
            $query->iconUrl = $post['iconurl'];
            $query->weight = $post['weight'];
            $query->opacity = $post['opacity'];
            $query->color = $post['color'];
            $query->dashArray = $post['dasharray'];
            $query->fillOpacity = $post['fillopacity'];
            $query->fillColor = $post['fillcolor'];
            $query->estado = $post['estado'];
            $query->geojson = $post['geojson'];
        $query->save();

        return Redirect::to('/admin/capasutiles')->with('status', 'Se ha editado correctamente la capa util.');
    }
    public function calendarValidation(){
        $fechas = PlanificacionInfo::selectRaw("created_at::date as fechas")->get();
        /*$fechas = json_encode($fechas);
        $fechas = str_replace('{"created_at":"', '', $fechas);
        $fechas = str_replace('[', '', $fechas);
        $fechas = str_replace(']', '', $fechas);
        $fechas = str_replace('}', '', $fechas);
        $fechas = str_replace('"','', $fechas);
        $fechas = explode(',', $fechas);*/
        return json_encode($fechas);
    }
    /*public function calendarValidationTEST(){
        $fechas = PlanificacionInfo::selectRaw("created_at::date")->get();
        $fechas = json_encode($fechas);
        $fechas = str_replace('{"created_at":"', '', $fechas);
        $fechas = str_replace('[', '', $fechas);
        $fechas = str_replace(']', '', $fechas);
        $fechas = str_replace('}', '', $fechas);
        $fechas = str_replace('"','', $fechas);
        $fechas = explode(',', $fechas);
        return $fechas;
    }*/
    public function areaTable(Request $request){
        $response = \DB::table('tags')
                    ->selectRaw('tags.desc')
                    ->where('tags.id_tag', '=', $request->area)
                    ->get();
        return json_encode($response);
    }

    public function DatosView()
    {
        return view('admin.datoscomplementarios');
    }
    public function NewDatosView()
    {
        return view('admin.datoscomplementarios_nuevo');
    }
    public function NewDato(Request $request)
    {
        $this->validate($request, [
            'html' => 'required',
            'desc_corta' => 'required',
            'desc_larga' => 'required'
        ]);

        $post = $request->all();
        $query = new DatosComplementarios;
            $query->html = $post['html'];
            $query->desc_corta = $post['desc_corta'];
            $query->desc_larga = $post['desc_larga'];
        $query->save();

        return Redirect::to('/admin/datoscomplementarios')->with('status', 'Se ha añadido correctamente el dato complementario.');
    }

    
}
