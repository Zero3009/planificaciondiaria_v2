@extends('layouts.orden')

@section('content')

<div class="row" >
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default" style="margin-top: 20px;">
			<div class="panel-heading">
				<h4 class="panel-title">Orden de trabajo - Previsualización</h4> 
			</div>

			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-sm-2">Area:</label>
					<div class="col-sm-4">
						<p class="form-control-static">{{$area->area->desc}}</p>
					</div>
					<label class="control-label col-sm-2">Usuario:</label>
					<div class="col-sm-4">
						<p class="form-control-static">{{Auth::user()->name}}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">Fecha de Ejecución de la Orden:</label>
                    <div class="col-md-4">     
                        <input placeholder="Desde:" required value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="min" readonly="true">
                    </div>
					<label class="control-label col-sm-2">Equipos de trabajo:</label>
                    <div class="col-md-4">
                        <select name="equipos" id="equipo" data-placeholder=" " class="form-control" style="width: 100%" required>
                            @foreach ($area->equipo_area as $equipo)
                                <option value="{{ $equipo->id_equipo }}">{{ $equipo->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
				</div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Seleccionar datos complementarios visibles</label>
                    <div class="col-sm-4">
                        <input type="checkbox" name="datos_complementarios[]">Dato 1 <br>
                        <input type="checkbox" name="datos_complementarios[]">Dato 1 <br>
                        <input type="checkbox" name="datos_complementarios[]">Dato 1 <br>
                        <input type="checkbox" name="datos_complementarios[]">Dato 1 <br>
                        <input type="checkbox" name="datos_complementarios[]">Dato 1 <br>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <input name="usuario" type="hidden" value="{{ Auth::user()->id }}">
                <input name="id_subarea" id="id_subarea" type="hidden" value="">
                <input name="origen" type="hidden" value="salida_almacen">

                <!--<input class="btn btn btn-warning" name="pendiente" type="submit" value="Guardar">-->
                <input class="btn btn btn-primary" name="despachar" type="submit" value="Generar orden de servicio">
            </div>
		</div>
	</div>
</div>

<div class="row" >
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Direcciones puntuales</h4> 
            </div>

            <div class="panel-body">
                <img src="/img/prueba.png" class="img-responsive" alt="Responsive image" style="width: 100%;height: 400px;">
                <table id="tabla-salidastock" class="table table-striped table-bordered"  cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Calle / zona</th>
                            <th>Descripción</th>
                            <th>Tipo de trabajo</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planificacion_info as $info)
                        <tr>
                            <td>{{$info->id_info}}</td>
                            <td>{{$info->callezona}}</td>
                            <td>{{$info->descripcion}}</td>
                            <td>{{$info->tipo_trabajo->desc}}</td>
                            <td>{{$info->horario}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Areas de trabajo</h4> 
            </div>
            <div class="panel-body">
                    <table id="tabla-salidastock" class="table table-striped table-bordered"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>ID</th>
                                <th>Calle / zona</th>
                                <th>Descripción</th>
                                <th>Tipo de trabajo</th>
                                <th>Horario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($planificacion_info as $info)
                            <tr>
                                <td><img src="/img/prueba.png" style="width: 200px;height: 200px;"></td>
                                <td class="text-center" style="vertical-align: middle;">{{$info->id_info}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$info->callezona}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$info->descripcion}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$info->tipo_trabajo->desc}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$info->horario}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
        var currentTime = new Date();
        // First Date Of the month 
        var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth() -7,1);

        $("#min").datepicker({
            numberOfMonths: 1,   
            showAnim: "slideDown",
            dateFormat: "yy-mm-dd",
            minDate: startDateFrom,
            onClose: function(selectedDate) {
                $("#max").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#max").datepicker({
            numberOfMonths: 1,      
            showAnim: "slideDown",
            dateFormat: "yy-mm-dd",
            maxDate: '0',
            onClose: function(selectedDate) {
                $("#min").datepicker("option", "maxDate", selectedDate);
            }
        });
    });

	//Completar tags
    $.getJSON("/ajax/tags-grupo/asignaciones", function (json) {
    	console.log(json);
        $.each(json, function(i, item) {
            $('#asignar_a').append('<option value="'+item.id_tag+'">'+item.desc+'</option>');
        });
    });
</script>

@endsection

