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

			       	<form method="POST" action="/admin/estilos/editar" accept-charset="UTF-8" class="form-horizontal">
					    <div class="row">
					    	<div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Area:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="area" placeholder="Area" readOnly value="{{$query2->desc}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Descripción:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="descripcion" placeholder="Descripcion" value="{{$query->descripcion}}"></input>
				                </div>
				            </div>

				     		<div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">iconUrl:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="iconurl" placeholder="iconUrl" value="{{$query->iconUrl}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Weight:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="weight" placeholder="Weight" value="{{$query->weight}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Opacity:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="opacity" placeholder="Opacity" value="{{$query->opacity}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">Color:</label>
				                <div class="col-md-8">
				                	<input class="form-control jscolor {hash:true}" name="color" placeholder="Color" value="{{$query->color}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">DashArray:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="dasharray" placeholder="dashArray" value="{{$query->dashArray}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">FillOpacity:</label>
				                <div class="col-md-8">
				                	<input type="text" class="form-control" name="fillopacity" placeholder="fillOpacity" value="{{$query->fillOpacity}}"></input>
				                </div>
				            </div>

				            <div class="form-group" style="margin-right: 0px;">
				                <label class="control-label col-md-4">FillColor:</label>
				                <div class="col-md-8">
				                	<input class="form-control jscolor {hash:true}" name="fillcolor" placeholder="Color" value="{{$query->fillColor}}"></input>
				                </div>
				            </div>

				            <input type="hidden" value="{{$query->id}}" name="id">
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

</script>


@stop