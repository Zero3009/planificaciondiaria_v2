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

				       	<form method="POST" action="/admin/etiquetas/nueva/post" accept-charset="UTF-8" class="form-horizontal">
					    <div class="row">
				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Nombre:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="desc" placeholder="Nombre"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Grupo:</label>
				                <div class="col-md-8">
				                	<div id="change">
						                <select class="form-control" name="grupo" id="grupo" required style="width: 90%;float: left;"> </select>
						            </div>
                                	<button class="btn btn-success" id="nuevo-grupo" style="float: right;width: 10%;"><b>+</b></button>
                            	</div>
				            </div>
				            <input type="hidden" name="_token" value="{{ csrf_token() }}">
					    </div>    
                </div>
                <div class="panel-footer">
                    <div class="text-center">
			            <input type="submit" class="btn btn-primary" value="Crear"></input>
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
    	$('#grupo').append('<option value="'+item.grupo+'">'+item.grupo+'</option>');
    });
});

$("#nuevo-grupo").click(function(e){
	e.preventDefault();
	$("#grupo").remove();
	$("#change").html("<input type='text' id='nuevo-grupo' class='form-control' name='grupo' style='width: 90%;float: left;'>")
	$("#nuevo-grupo").focus();
});

</script>


@stop