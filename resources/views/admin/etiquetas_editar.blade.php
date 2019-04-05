@extends('layouts.administracion')

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Gestionar etiquetas</h3>
                </div>
                <div class="panel-body">
                        @if (count($errors) > 0)
					        <div class="alert alert-danger">
					            <strong>Whoops!</strong> There were some problems with your input.<br><br>
					            <ul>
					                @foreach ($errors->all() as $error)
					                    <li>{{ $error }}</li>
					                @endforeach
					            </ul>
					        </div>
					    @endif

				       	<form method="POST" action="/admin/etiquetas/editar" accept-charset="UTF-8" class="form-horizontal">
						    <div class="row">
					            <div class="form-group" style="margin-right: 0px;">
					                <label class="control-label col-md-4">Nombre:</label>
					                <div class="col-md-8">
					                	<input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{$query->desc}}"></input>
					                </div>
					            </div>

					            <div class="form-group" style="margin-right: 0px;">
					                <label class="control-label col-md-4">Grupo:</label>
					                <div class="col-md-8">
						                <select class="form-control" name="grupo" id="grupo" required>
	                                		<option value="{{$query->grupo}}" class="grupoactivo" active>{{$query->grupo}}</option>
	                                	</select>
                                	</div>
					            </div>
					            <div class="form-group" style="margin-right: 0px;">
					                <label class="control-label col-md-4">Estado:</label>
					                <div class="col-md-8">
										<input type="radio" name="estado" value="true" <?php if($query->estado == true) { echo 'checked=checked'; } ?>> Activado<br>
										<input type="radio" name="estado" value="false" <?php if($query->estado == false) { echo 'checked=checked'; } ?>> Desactivado 
					                </div>
					            </div>
					            <input type="hidden" value="{{$query->id_tag}}" name="id">
					            <input type="hidden" name="_token" value="{{ csrf_token() }}">
						    </div>    
                </div>
                <div class="panel-footer">
                    <div class="text-center">
			            <input type="submit" class="btn btn-primary" value="Editar"></input>
			        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')

<script>

$.getJSON("/ajax/grupos", function (json) {
    $.each(json, function(i, item) {
    	if(item.grupo != $(".grupoactivo").val()){
    		$('#grupo').append('<option value="'+item.grupo+'">'+item.grupo+'</option>');
    	}
    });
});

</script>


@stop