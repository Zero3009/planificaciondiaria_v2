@extends('layouts.administracion')

@section('main-content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                	<div class="col-md-4" style="float: left;">
                		<h3 class="panel-title">Nuevo equipo de trabajo</h3>
                	</div>
            	</div>
            </div>
            
            <form method="POST" action="/admin/equipo/nuevo/post" accept-charset="UTF-8" class="form-horizontal">
                <div class="panel-body">    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4">Descripción:</label>
                                <div class="col-sm-8">
                                    <input id="descripcion "class="form-control" type="text" name="descripcion" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input class="btn btn btn-primary" tabindex="1" type="submit" value="Nuevo">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
		</div>
	</div>
</div>
@stop