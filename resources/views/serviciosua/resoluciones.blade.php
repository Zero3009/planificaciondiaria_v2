@extends('layouts.serviciosua')

@section('main-content')
<div class="loading" style="display: none;">Loading&#8230;</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <h3 class="panel-title">Servicio de resolución</h3>
            </div>
            <div class="panel-body">
            	<div class="row">
	            	<form method="POST" action="#" accept-charset="UTF-8" class="form-horizontal">
				    	<div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Cargar archivo excel:</label>
			                <div class="col-md-3">
			                	<input type="file" name="xlfile" id="xlf" class="btn btn-primary" />
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Parametros para obtener token:</label>
			            </div>

			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Usuario API</label>
			                <div class="col-md-2">
			                	<input class="form-control" type="text" id="usuario_api" disabled="true" />
			                </div>
			                <label class="control-label col-md-2">Password</label>
			                <div class="col-md-2">
			                	<input class="form-control" type="Password" id="password_api" placeholder="***********" disabled="true" />
			                </div>
			                <div class="col-md-3">
			                	<button class="btn btn-primary" id="get_token" style="float: right;" disabled="true">Obtener token</button>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Token</label>
			                <div class="col-md-3">
			                	<input class="form-control" type="text" id="token" value="No informado" readonly="true" disabled="true">
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">ID del area</label>
			                <div class="col-md-2">
			                	<input class="form-control" type="text" id="id_area" placeholder="Para PYP: 2098" disabled="true">
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Tipo de resolucion</label>
			                <div class="col-md-2">
			                	<select class="form-control" id="tipo_resolucion" placeholder="" disabled="true">
			                		<option value="No informado">No informado</option>
			                		<option value="1">Resuelto</option>
			                		<option value="3">Cerrado</option>
			                	</select>
			                </div>
			                <div class="col-md-3" id="cierre">
			                	<select class="form-control" id="tipo_cierre" placeholder="" disabled="true">
			                		<option value="0">No informado</option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Fecha de resolución</label>
			                <div class="col-md-2">
			                	<input placeholder="Fecha:" value="<?php echo \Carbon\Carbon::now()->format('d/m/Y');?>" type="text" class="form-control" id="fecha" readonly="true" disabled="true">
			                </div>
			            </div>	
			            <div class="form-group" style="margin-right: 0px;">
			                <div class="col-md-3">
			                	<button class="btn btn-primary" id="obtener_id" style="float: right;" disabled="true">Obtener ID de las solicitudes</button>
			                </div>
			                <div class="col-md-2">
			                	<input class="form-control" id="completado" type="text" readonly="true" value="No completado" disabled="true">
			                </div>
			                <label class="control-label col-md-1">No aptos:</label>
			                <div class="col-md-2">
			                	<input class="form-control" id="cuenta_errores" type="number" readonly="true" disabled="true" value="0">
			                </div>
			                <label class="control-label col-md-1">Aptos:</label>
			                <div class="col-md-2">
			                	<input class="form-control" id="cuenta_exitos" type="number" readonly="true" disabled="true" value="0">
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">

							<label class="control-label col-md-3">Procesados correctamente:</label>
			                <div class="col-md-2">
			                	<input class="form-control" id="cuenta_true" type="number" readonly="true" disabled="true" value="0">
			                </div>
			                <label class="control-label col-md-1">Errores:</label>
			                <div class="col-md-2">
			                	<input class="form-control" id="cuenta_false" type="number" readonly="true" disabled="true" value="0">
			                </div>
			            	<div class="col-md-3">
			                	<button class="btn btn-success" id="procesar" style="float: right;" disabled="true">Procesar</button>
			                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			                </div>
			            </div>
			        </form>
		        </div>
		        <div class="row">
		        	<div id="htmlout">
                		<div class="box">
		                    <div class="box-body">
		                        <table class="table table-striped table-bordered dataTable" width="100%" id="tabla-import">
		                            <thead>
		                                <tr>
		                                	<th>ID solicitud</th>
		                                    <th>Numero</th>
		                                    <th>Año</th>
		                                    <th>Leyenda</th>
		                                    <th>Fecha de resolución</th>
		                                    <th>Tipo de resolución</th>
		                                    <th>Estado</th>
		                                </tr>
		                            </thead>
		                            <tbody id="import-tabla"></tbody>
		                        </table>
		                    </div>
		                </div>
                	</div>
		        </div>
            </div>
            <div class="panel-footer">
                Pie del panel
            </div>
        </div>
    </div>

	<div class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Seleccionar hoja</h4>
				</div>
				<div class="modal-body">
					
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


</div>
@stop

@section('js')

<script>
	
	//CARGAR EXCEL Y CONVERTIRLO EN TABLA
	function fixdata(data) {
	  var o = "", l = 0, w = 10240;
	  for(; l<data.byteLength/w; ++l) o+=String.fromCharCode.apply(null,new Uint8Array(data.slice(l*w,l*w+w)));
	  o+=String.fromCharCode.apply(null, new Uint8Array(data.slice(l*w)));
	  return o;
	}
	var rABS = true;
	function handleFile(e) {
		var files = e.target.files;
		var i,f;
		for (i = 0; i != files.length; ++i) {
	    f = files[i];
		    var reader = new FileReader();
		    var name = f.name;
		    reader.onload = function(e) {
		      var data = e.target.result;

		      var workbook;
		      if(rABS) {
		        /* if binary string, read with type 'binary' */
		        workbook = XLSX.read(data, {type: 'binary'});
		      } else {
		        /* if array buffer, convert to base64 */
		        var arr = fixdata(data);
		        workbook = XLSX.read(btoa(arr), {type: 'base64'});
		      }
				/* DO SOMETHING WITH workbook HERE */
				$("#import-tabla").html("");
				$('.modal').modal();
				$('.modal').on('shown.bs.modal', function () {
				  	$.each( workbook.SheetNames, function( numero, nombre ) {
				  		$('.modal-body').append('<input type="radio" name="hoja_importacion" value="'+nombre+'">'+nombre+'<br>');
					});
				})
				
				$(document).on('change',"input[name=hoja_importacion]:radio",function(){
					$('.modal').modal('hide');
					to_json(workbook, $(this).val());
					$("#usuario_api").focus();
				});

				$("#xlf").attr('disabled', true);
				$("#usuario_api").attr('disabled', false);
				$("#password_api").attr('disabled', false);
				$("#get_token").attr('disabled', false);
				$("#token").attr('disabled', false);
		    };
		    reader.readAsBinaryString(f);
	    }
	}
	xlf.addEventListener('change', handleFile, false);
	function to_json(workbook, hoja) {
		var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[hoja]);
		$.each( roa, function( key, columnas ) {
			$('#import-tabla').append('<tr id="tr-'+key+'"></tr>');
			$('#tr-'+key).append('<td class="id_solicitud">No declarado</td>');
		  	$.each( columnas, function( key2, value ) {
		  		if(key2 == "id_solicitud"){
		  			$('#tr-'+key+' .id_solicitud').text(value);
		  		}
		  		else{
		  			$('#tr-'+key).append('<td>'+value+'</td>');
		  		}
			});

			$('#tr-'+key).append('<td class="fecha">No declarado</td>');
			$('#tr-'+key).append('<td>No declarado</td>');
			$('#tr-'+key).append('<td>No procesado</td>');
		});

		var rowCount = $('#tabla-import tr').length;
		$("#contador").val(rowCount-1);
	}



	//OBTENER TOKEN
	$("#get_token").click(function(e){
		$(".loading").attr("style", "display: block;");
		e.preventDefault();
        var user = $("#usuario_api").val();
		var password = $("#password_api").val();
		var data = { 
			"username": user, 
			"password": password 
		};
		$.ajax({
            type: 'GET',
            url: "/ajax/serviciosua/token/",
            dataType: 'json',
            data: data,
            contentType: 'application/json; charset=utf-8',
            success: function(response) {
            	console.log(response);
            	$("#token").val(response.token);
                $("#usuario_api").attr('readOnly', 'true');
                $("#password_api").attr('readOnly', 'true');
                $("#get_token").attr('disabled', true);
                $("#id_area").attr('disabled', false);
                $("#id_area").focus();
                $(".loading").attr("style", "display: none;");
            },
            error: function(error) {
                alert("Ha ocurrido un error, por favor vuelva a intentar.");
                $(".loading").attr("style", "display: none;");
            }
        });
	});

	$("#id_area").focusout(function(){
		$("#id_area").attr('disabled', true);
		$("#tipo_resolucion").attr('disabled', false);
		$("#tipo_resolucion").focus();	
	});

	$('#tipo_resolucion').change(function() {
		if ($('#tipo_resolucion').val() == "3"){
			$("#tipo_cierre").append('<option value="6">La temática no es de competencia municipal</option><option value="7">No se constató la situación reportada</option><option value="8">No es factible lo solicitado</option><option value="9">Solicitud precedente en curso</option>');
			$("#tipo_cierre").attr('disabled', false);
			$("#tipo_cierre").focus();
			$('#tipo_cierre').change(function() {
				$("#fecha").attr('disabled', false);
				$("#fecha").focus();
				$("#tipo_cierre").attr('disabled', true);
			});
		}
		else{
			$("#fecha").attr('disabled', false);
			$("#fecha").focus();
		}
		$("#tipo_resolucion").attr('disabled', true);
	    
		$('#import-tabla > tr').each(function(i, tr) {
			$(this).find('td:eq(5)').text($("#tipo_resolucion option:selected").text());
	   	});
	});
	
	//Obtener ID de cada una de las solicitudes
	$("#obtener_id").click(function(e){
		e.preventDefault();
		var contador = 0;
		$('#import-tabla > tr').each(function(i, tr) {
			var ids = $(this).find('td:eq(0)').text();
			var nro = $(this).find('td:eq(1)').text();
			var anio = $(this).find('td:eq(2)').text();

			if(ids == 'No declarado' || !$.isNumeric(ids)){
				$.ajax({
		            type: 'GET',
		            url: "/ajax/getidsolicitudes/"+nro+"/"+anio,
		            dataType: 'json',
		            contentType: 'application/json; charset=utf-8',
		            success: function(response) {
		            	contador += 1;
		            	if(response.estado){
		            		$(tr).addClass("danger");
		            		$(tr).find('td:eq(6)').text("No apto");
		            		valor = $("#cuenta_errores").val();
		            		$("#cuenta_errores").val(parseInt(valor)+1);
		            	}
		            	else{
		            		$(tr).addClass("info");    		
		            		$(tr).find('td:eq(0)').text(response.id);
		            		$(tr).find('td:eq(6)').text("Apto");
		            		valor = $("#cuenta_exitos").val();
		            		$("#cuenta_exitos").val(parseInt(valor)+1);
		            		
		            	}
		            	if(contador == $('#import-tabla > tr').length){
		            		$("#completado").val("Completado");
		            	}
		            },
		            error: function(error) {
		                alert("Ha ocurrido un error, por favor vuelva a intentar.");
	                	$(".loading").attr("style", "display: none;");
		            }
		        });
	        }
	        else{
	        	$(tr).addClass("info");    		
        		$(tr).find('td:eq(6)').text("Apto");
        		valor = $("#cuenta_exitos").val();
        		$("#cuenta_exitos").val(parseInt(valor)+1);
			}
			$("#obtener_id").attr('disabled', true);
			$("#procesar").attr('disabled', false);
			$("#procesar").focus();
		});
	});

	//Procesar las solicitudes cargadas en la tabla para intervernirlas
	$("#procesar").click(function(e){
		e.preventDefault();

		var total = 0;
		var count = 0;
		$(".loading").attr("style", "display: block;");

		$("#import-tabla").find('tr').each(function() {
			id_solicitud = $(this).find('td:eq(0)').text();
			tipo_resolucion = $("#tipo_resolucion").val();
			tipo_desc = $("#tipo_resolucion").find('option:selected').text();
			leyenda = $(this).find('td:eq(3)').text();
			fecha_intervencion = $(this).find('td:eq(4)').text();
			token = $("#token").val();
			id_area = $("#id_area").val();
			_token = $('input[name=_token]').val();
			id_tr = $(this).attr('id');
			id_motivo_cierre = $("#tipo_cierre").val(); 
			nro = $(this).find('td:eq(1)').text();
			anio = $(this).find('td:eq(2)').text();

			if(id_solicitud != "No declarado"){
				total++;
			}
			var data2 = {
				"nro": nro,
				"anio": anio,
				"id_solicitud": id_solicitud,
				"tipo_resolucion": tipo_resolucion,
				"leyenda": leyenda,
				"token": token,
				"id_area": id_area,
				"fecha_intervencion": fecha_intervencion,
				"_token": _token,
				"id_tr": id_tr,
				"id_motivo_cierre": id_motivo_cierre,
				"tipo_desc": tipo_desc
			};

			$.ajaxSetup({
                header:$('meta[name="_token"]').attr('content')
            })
			$.ajax({
			    type:"POST",
			    url:'/ajax/serviciosua/resolver',
			    data: data2,
			    dataType: 'json',
			    success: function(response) {
			    	console.log(response);
			        count++;
			        if(response.serv == "\"ok\""){
			        	$("#"+response.id_tr).removeClass("info");
			        	$("#"+response.id_tr).addClass("success");
			        	$("#"+response.id_tr).find('td:eq(6)').text("Ok");
			        	$("#cuenta_true").val(parseInt($("#cuenta_true").val())+1);
			        }
			        else{
			        	$("#"+response.id_tr).removeClass("info");
			        	$("#"+response.id_tr).addClass("danger");
			        	$("#"+response.id_tr).find('td:eq(6)').html("<a href='https://sua.rosario.gob.ar/sua-webapp/solicitud/ver.do?accion=ver&id="+$("#"+response.id_tr).find('td:eq(0)').text()+"&origen=busqueda'>VER</a> ");
			        	$("#cuenta_false").val(parseInt($("#cuenta_false").val())+1);
			        }
		            if(count == total){ 
		                $(".loading").attr("style", "display: none;");
		            }
			    },
			    error: function(error) {
			        console.log(error);
			    }
			});
		});
	});

	$("#fecha").datepicker({
        numberOfMonths: 1,   
        showAnim: "slideDown",
        dateFormat: "dd/mm/yy",
        onClose: function(selectedDate) {
			$('#import-tabla > tr').each(function(i, tr) {
				$(this).find('td:eq(4)').text($("#fecha").val());
				$("#obtener_id").attr('disabled', false);
				$("#obtener_id").focus();
		   	});
        }
    });

</script>

@stop