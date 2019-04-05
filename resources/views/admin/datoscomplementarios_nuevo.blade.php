@extends('layouts.administracion')

@section('main-content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                	<div class="col-md-4" style="float: left;">
                		<h3 class="panel-title">NUEVO DATO COMPLEMENTARIO</h3>
                	</div>
            	</div>
            </div>
            
            <form method="POST" action="/admin/datoscomplementarios/nuevo/post" accept-charset="UTF-8" class="form-horizontal">
                <div class="panel-body">    
                    <div class="row">
                        <div class="col-sm-6"> 
                            <label class="control-label col-sm-1">HTML:</label>
                                <div class="col-sm-11">
                            <textarea id="html" class="form-control" rows="4" name="html"></textarea>
                                </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4">Descripción corta:</label>
                                <div class="col-sm-8">
                                    <input id="desc_corta "class="form-control" type="text" name="desc_corta">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4">Descripción larga:</label>
                                <div class="col-sm-8">
                                    <input id="desc_larga" class="form-control" type="text" name="desc_larga">
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