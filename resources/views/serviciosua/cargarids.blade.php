@extends('layouts.serviciosua')

@section('main-content')
<div class="loading" style="display: none;">Loading&#8230;</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
        	<form method="POST" action="/serviciosua/procesarids" accept-charset="UTF-8" class="form-horizontal">
        		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
	                <h3 class="panel-title">Servicio de intervenciones</h3>
	            </div>
	            <div class="panel-body">
	            	<div class="row">
				    	<div class="form-group" style="margin-right: 0px;">
			                <label class="control-label col-md-3">Cargar archivo excel:</label>
			                <div class="col-md-3">
			                	<input type="file" name="xlfile" id="xlf" class="btn btn-primary" />
			                </div>
			            </div>
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
	                <input type="submit" value="Enviar" class="form-control">
	            </div>
        	</form>
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
				});
		    };
		    reader.readAsBinaryString(f);
	    }
	}
	xlf.addEventListener('change', handleFile, false);
	function to_json(workbook, hoja) {
		var roa = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[hoja]);
		$.each( roa, function( key, columnas ) {
			$('#import-tabla').append('<tr id="tr-'+key+'"></tr>');
		  	$.each( columnas, function( key2, value ) {
		  		if(key2 == "id"){
		  			$('#tr-'+key).append('<td>'+value+'<input type="hidden" value="'+value+'" name="id[]" ></td>');
		  		} else if(key2 == "numero"){
		  			$('#tr-'+key).append('<td>'+value+'<input type="hidden" value="'+value+'" name="nro[]" ></td>');
		  		} else if(key2 == "anio"){
		  			$('#tr-'+key).append('<td>'+value+'<input type="hidden" value="'+value+'" name="anio[]" ></td>');
		  		}
		  	});
		});
	}
</script>

@stop