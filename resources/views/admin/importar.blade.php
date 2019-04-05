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
			            <div class="form-group">
			                <label class="control-label col-md-12" style="text-align: center;"><h3>Mapear campos</h3></label>
			            </div>

			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Area</label>
			                <div class="col-md-2">
			                	<select class="form-control area" id="area"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Calle/Zona</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="callezona"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Descripción</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="descripcion"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Tipo de trabajo</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="tipo_trabajo"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;" id="mapear_valores_trabajo">
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Horario</label>
			                <div class="col-sm-10">
                                <div class="sliders_step1" style="padding-top: 10px;padding-bottom: 10px;"><div id="slider-range"></div></div><input class="time" type="hidden" value="450,750" />
                                <div class="slider-time" style="text-align: center;">7:30 AM - 12:30 PM</div>
                            </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Corte de calzada</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="corte_calzada"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;" id="mapear_valores_calzada">
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Coordenadas X</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="coord_x"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">Coordenadas Y</label>
			                <div class="col-md-2">
			                	<select class="form-control completarcampos" id="coord_y"> 
			                		<option value=""></option>
			                	</select>
			                </div>
			            </div>
			            <div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-2">¿Convertir coordenadas?</label>
			                <div class="col-md-4">
			                	<input type="checkbox" name="convertir" id="convertir" value="ok">De EPGS 22185 a EPGS 4326</input>
			                </div>
			            </div>
			            
			            <div class="form-group" style="margin-right: 0px;">
			            	<div class="col-md-12">
			                	<button class="btn btn-primary" id="cargar" style="float: right;">Cargar tabla</button>
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
		                                	<th>Area</th>
		                                    <th>Calle/Zona</th>
		                                    <th>Descripción</th>
		                                    <th>Tipo de trabajo</th>
		                                    <th>Horario</th>
		                                    <th>Corte de calzada</th>
		                                    <th>Coordenadas X</th>
		                                    <th>Coordenadas Y</th>
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
                <div class="col-md-12">
                	<button class="btn btn-success" id="procesar" style="float: right;">Procesar</button>
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
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

	var globalSheet = null;
	var firstProjection = "+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
    var secondProjection = "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs ";
	
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
				});

				$("#xlf").attr('disabled', true);
		    };
		    reader.readAsBinaryString(f);
	    }
	}
	xlf.addEventListener('change', handleFile, false);

	function to_json(workbook, hoja) {
		var roa = XLSX.utils.sheet_to_json(workbook.Sheets[hoja]);
		globalSheet = roa;
		var keys = Object.keys(roa[0]);
		$.each( keys, function( i, val ) {
			$( ".completarcampos" ).append( "<option value='"+val+"'>"+val+"</option>" );
		});
	}

	function onlyUnique(value, index, self) { 
	    return self.indexOf(value) === index;
	}

	$("#tipo_trabajo").change(function(){
		tipos_trabajos = new Array();
		$.each(globalSheet, function(i, val){
			tipos_trabajos.push(val[$("#tipo_trabajo").val()]);
		});	

		//eliminar duplicados
		if(tipos_trabajos.length > 0){
			tipos_trabajos = unique(tipos_trabajos);
		}

		$("#mapear_valores_trabajo").html('');
		$.each(tipos_trabajos, function(i,val){
			$("#mapear_valores_trabajo").append('<div class="form-group"><label class="control-label col-md-4"></label><label class="control-label col-md-2">'+val+'</label><div class="col-md-2"><select class="form-control completarcampos_tipotrabajo" name="tipo_trabajo"> <option value=""></option></select></div></div>');
		});
		completarcampos("tipo_trabajo", "completarcampos_tipotrabajo");
	});

	$("#corte_calzada").change(function(){
		corte_calzada = new Array();
		$.each(globalSheet, function(i, val){
			corte_calzada.push(val[$("#corte_calzada").val()]);
		});	

		//Eliminar duplicados
		if(corte_calzada.length > 0){
			corte_calzada = unique(corte_calzada);
		}

		$("#mapear_valores_calzada").html('');
		$.each(corte_calzada, function(i,val){
			$("#mapear_valores_calzada").append('<div class="form-group"><label class="control-label col-md-4"></label><label class="control-label col-md-2">'+val+'</label><div class="col-md-2"><select class="form-control completarcampos_cortecalzada" name="tipo_cortecalzada"> <option value=""></option></select></div></div>');
		});
		completarcampos("corte_calzada", "completarcampos_cortecalzada");
	});

	//Cargar las solicitudes cargadas en la tabla para intervernirlas
	$("#cargar").click(function(e){
		e.preventDefault();
		$.each( globalSheet, function( key, columnas ) {
			$('#import-tabla').append('<tr class="info" id="tr-'+key+'" data-id="'+key+'"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
		  	$.each( columnas, function( key2, value ) {

		  		$('#tr-'+key).find('td:eq(0)').html($("#area").val());
		  		$('#tr-'+key).find('td:eq(4)').html($(".slider-time").html());

				if(key2 == $("#callezona").val()){
					$('#tr-'+key).find('td:eq(1)').html(value);
					if($('#coord_x').val() == "" || $('coord_y').val() == "")
					{
						$.getJSON('https://ws.rosario.gob.ar/ubicaciones/public/geojson/ubicaciones/all/all/'+encodeURI(value), function(json){
							console.log(json);
							if(json.features.length > 1){
								$('#tr-'+key).find('td:eq(6)').html('<select class="selectubicacion form-control" name="ubi"><option value=""></option></select>');
								var pip = 0;
								for(var i = 0; i < json.features.length;i++)
								{
									if(json.features[i].properties.subtipo == "DIRECCIÓN")
									{
										pip = pip +1;
										$('#tr-'+key).find('td:eq(6) .selectubicacion').append('<option value="'+ json.features[i].properties.name +'" data-coordx="'+ json.features[i].geometry.coordinates[0] +'" data-coordy="'+ json.features[i].geometry.coordinates[1] +'">'+json.features[i].properties.name+'</option>');		
									}

								}
								if(pip == 0)
								{
									$('#tr-'+key).find('td:eq(6)').empty();
								}
							}else if(json.features.length == 1)
							{
								if(json.features[0].properties.subtipo == "DIRECCIÓN")
								{
									$('#tr-'+key).find('td:eq(6)').html(json.features[0].geometry.coordinates[0]);
									$('#tr-'+key).find('td:eq(7)').html(json.features[0].geometry.coordinates[1]);
									$('#tr-'+key).find('td:eq(8)').html("OK");
								}
							}
						});
					}
					else
					{
						if(key2 == $("#coord_x").val()){
							$('#tr-'+key).find('td:eq(6)').html(value);
						}
						if(key2 == $("#coord_y").val()){
							$('#tr-'+key).find('td:eq(7)').html(value);
						}
					}
				}

				if(key2 == $("#descripcion").val()){
					$('#tr-'+key).find('td:eq(2)').html(value);
				}

				if(key2 == $("#tipo_trabajo").val()){
					$('#tr-'+key).find('td:eq(3)').html(value.valor);
				}

				if(key2 == $("#corte_calzada").val()){
					$('#tr-'+key).find('td:eq(5)').html(value.valor);
				}
				
				
				
			});
		});

		$("#cargar").attr("disabled", true);
	});

	$(document).on('change','.completarcampos_tipotrabajo',function(cb){
		select = this;
		$.each( globalSheet, function( key, columnas ) {
		  	$.each( columnas, function( key2, value ) {
		  		if(key2 == $("#tipo_trabajo option:selected").text()){
	  				globalSheet[key][key2] = { name : value, valor : $('option:selected', select).val() };
		  		}
			});
		});
	});

	$(document).on('change','.completarcampos_cortecalzada',function(cb){
		select = this;
		$.each( globalSheet, function( key, columnas ) {
		  	$.each( columnas, function( key2, value ) {
		  		if(key2 == $("#corte_calzada option:selected").text()){
	  				globalSheet[key][key2] = { name : value, valor : $('option:selected', select).val() };
		  		}
			});
		});
	});

	$(document).on('change','.selectubicacion',function(cb){
		ubicacion_nombre = $(this).val();
		coordx = $(this).find(':selected').data("coordx");
		coordy = $(this).find(':selected').data("coordy");

		$(this).parent().parent().find('td:eq(7)').html(coordy);
		$(this).parent().parent().find('td:eq(1)').html(ubicacion_nombre);
		$(this).parent().parent().find('td:eq(8)').html("OK");
		$(this).parent().html(coordx);
		
	});

	$("#procesar").click(function(){

		$("#import-tabla").find('tr').each(function() {

			area = $(this).find('td:eq(0)').text();
			callezona = $(this).find('td:eq(1)').text();
			descripcion = $(this).find('td:eq(2)').text();
			tipo_trabajo = $(this).find('td:eq(3)').text();
			horario = $(this).find('td:eq(4)').text();
			corte_calzada = $(this).find('td:eq(5)').text();
			tipo_geometria = "Point";
			id_usuario = "{{Auth::user()->id}}";
			token = "{{ csrf_token() }}";
			fecha_planificada = null;
			id_tr = $(this).data('id');

			if($('#convertir').is(':checked')){
				geometrias = proj4(firstProjection,secondProjection,[$(this).find('td:eq(6)').text(),$(this).find('td:eq(7)').text()]);
			} else {
				geometrias = [$(this).find('td:eq(6)').text(),$(this).find('td:eq(7)').text()];
			}

			$.ajax({
	            type:"POST",
	            url:'/planificacion/guardar',
	            data: { 'geometrias': geometrias, 'tipo_geometria': tipo_geometria, 'callezona': $.trim(callezona), 'descripcion': descripcion, 'id_area': area, 'id_tipo_trabajo': tipo_trabajo, 'fecha_planificada': fecha_planificada , 'horario': horario, 'id_corte_calzada': corte_calzada, '_token': token, 'id_usuario': id_usuario, 'datos_complementarios' : null, 'id_tr': id_tr },
	            dataType: 'json',
	            success: function(data) {
	                if(data.msg == "Se cargo correctamente la geometria"){
	                	$("#tr-"+data.id_tr).removeClass("info");
	                	$("#tr-"+data.id_tr).addClass("success");
			        	$("#tr-"+data.id_tr).find('td:eq(8)').text("Ok");
	                }else {
	                	$("#tr-"+data.id_tr).find('td:eq(8)').html(data.toString());
	                	$("#tr-"+data.id_tr).removeClass("info");
			        	$("#tr-"+data.id_tr).addClass("danger");
	                }
	            },
	            error: function(xhr, status, error){
	            	console.log(xhr);
			    }
	        });

        });
	});

	function unique(list) {
	    var result = [];
	    $.each(list, function(i, e) {
	        if ($.inArray(e, result) == -1) result.push(e);
	    });
	    return result;
	}

	completarcampos("area", "area");

	function completarcampos(filtro, clase){
		$.getJSON("/ajax/tags-grupo/"+filtro, function (json) {
	        $.each(json, function(i, item) {
	            $('.'+clase).append('<option value="'+item.id_tag+'">'+item.desc+'</option>');
	        });
	    });
	}

	//Iniciar slider dentro del modal
    function initSlider(time1, time2){
        if(!time1 || !time2){
            time1 = 450;
            time2 = 750;
        }
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 1440,
            step: 15,
            values: [time1, time2],
            slide: function (e, ui) {
                var hours1 = Math.floor(ui.values[0] / 60);
                var minutes1 = ui.values[0] - (hours1 * 60);
                if (hours1.length == 1) hours1 = '0' + hours1;
                if (minutes1.length == 1) minutes1 = '0' + minutes1;
                if (minutes1 == 0) minutes1 = '00';
                if (hours1 >= 12) {
                    if (hours1 == 12) {
                        hours1 = hours1;
                        minutes1 = minutes1 + " PM";
                    } else {
                        hours1 = hours1 - 12;
                        minutes1 = minutes1 + " PM";
                    }
                } else {
                    hours1 = hours1;
                    minutes1 = minutes1 + " AM";
                }
                if (hours1 == 0) {
                    hours1 = 12;
                    minutes1 = minutes1;
                }
                var hours2 = Math.floor(ui.values[1] / 60);
                var minutes2 = ui.values[1] - (hours2 * 60);
                if (hours2.length == 1) hours2 = '0' + hours2;
                if (minutes2.length == 1) minutes2 = '0' + minutes2;
                if (minutes2 == 0) minutes2 = '00';
                if (hours2 >= 12) {
                    if (hours2 == 12) {
                        hours2 = hours2;
                        minutes2 = minutes2 + " PM";
                    } else if (hours2 == 24) {
                        hours2 = 11;
                        minutes2 = "59 PM";
                    } else {
                        hours2 = hours2 - 12;
                        minutes2 = minutes2 + " PM";
                    }
                } else {
                    hours2 = hours2;
                    minutes2 = minutes2 + " AM";
                }
                $('.slider-time').html(hours1 + ':' + minutes1+' - '+hours2 + ':' + minutes2);

                $('.time').val(ui.values[0]+','+ui.values[1]);
            }
        });
    }

	initSlider();

</script>

@stop