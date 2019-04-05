@extends('layouts.administracion')

@section('main-content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                	<div class="col-md-4" style="float: left;">
                		<h3 class="panel-title">DATOS COMPLEMENTARIOS</h3>
                	</div>
                	<div class="col-md-8" style="float: right;">
                         <a class="btn btn-success" href="/admin/datoscomplementarios/nuevo" style="float: right;">
                        <i class="fa fa-plus"></i> Nuevo</a>
                	</div>
            	</div>
            </div>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success" id="ocultar">
                        {{ session('status') }}
                    </div>
                @endif
                <table class="table-striped table-bordered" width="100%" id="table">
                    <thead>
                        <tr>
                            <!--<th>id</th>-->
                            <th>Descripción corta</th>
                            <th>Descripción larga</th>
                            <th>html</th>
                        </tr>
                    </thead>
                </table>
            </div>
		</div>
	</div>
</div>
@stop
@section('js')
<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '/datatables/datoscomplementariosall',
        "columns":[
            //{data: 'id', name: 'id'},
            {data: 'desc_corta', name: 'desc_corta'},
            {data: 'desc_larga', name: 'desc_larga'},
            {data: 'html', name: 'html', render: $.fn.dataTable.render.text()}
        ],
        /*"columnDefs": [
            {   
                className: "id",
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],*/
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        },
    }); 
});
</script>

@stop