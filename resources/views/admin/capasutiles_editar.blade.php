@extends('layouts.administracion')

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Gestionar estilos</h3>
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

			       	<form method="POST" action="/admin/capasutiles/editar" accept-charset="UTF-8" class="form-horizontal" id="capautil">
					    <div class="row">
					    	<div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Nombre de la capa:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="nombre_capa" value="{{$capaxid->nombre}}" required="true"></input>
				                </div>
				            </div>

				     		<div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">iconUrl:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="iconurl" placeholder="URL en caso de ser capa de puntos" value="{{$capaxid->iconUrl}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Weight:</label>
				                <div class="col-md-8">
				                	<input type="number" min="0" class="form-control" name="weight" placeholder="8.00" value="{{$capaxid->weight}}" required="true" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Opacity:</label>
				                <div class="col-md-8">
				                	<input type="number" min="0" max="1" step="0.05" class="form-control" name="opacity" placeholder="1.00" value="{{$capaxid->opacity}}" required="true" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Color:</label>
				                <div class="col-md-8">
				                	<input class="form-control jscolor {hash:true}" name="color" placeholder="Color" value="{{$capaxid->color}}" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">DashArray:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="dasharray" placeholder="15,10,5,10" value="{{$capaxid->dashArray}}" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">FillOpacity:</label>
				                <div class="col-md-8">
				                	<input type="number" min="0" max="1" step="0.05" class="form-control" name="fillopacity" placeholder="0.40" value="{{$capaxid->fillOpacity}}" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">FillColor:</label>
				                <div class="col-md-8">
				                	<input class="form-control jscolor {hash:true}" name="fillcolor" placeholder="Color" value="{{$capaxid->fillColor}}" required="true"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Estado:</label>
				                <div class="col-md-8">
									<input type="radio" name="estado" value="true" <?php if($capaxid->estado == true) { echo 'checked=checked'; } ?>> Activado<br>
									<input type="radio" name="estado" value="false" <?php if($capaxid->estado == false) { echo 'checked=checked'; } ?>> Desactivado 
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Geojson:</label>
				                <div class="col-md-8">
				                	<textarea rows="4" cols="50" name="geojson" form="capautil" required="true">{{$capaxid->geojson}}</textarea>
				                </div>
				            </div>
					    </div>    
                </div>
                <div class="panel-footer">
                    <div class="text-center">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    	<input name="id" type="hidden" value="{{$capaxid->id}}">
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

</script>


@stop