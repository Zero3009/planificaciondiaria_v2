@extends('layouts.administracion')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                    <h3 class="panel-title" style="margin-top: 10px;" title="asdasda">Gestionar estilos</h3>
            </div>

            <div class="panel-body">
                <!-- Mensajes de exito-->
                @if (session('status'))
                    <div class="alert alert-success" id="ocultar">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="box">
                    <div class="box-body">
                        <table class="table-striped table-bordered" width="100%" id="tabla-estilos">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Area</th>
                                    <th>Descripcion</th>
                                    <th>iconUrl</th>
                                    <th>Weight</th>
                                    <th>Opacity</th>
                                    <th>Color</th>
                                    <th>DashArray</th>
                                    <th>FillOpacity</th>
                                    <th>fillColor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')

<script>

$(document).ready( function () {

var table = $('#tabla-estilos').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '/datatables/estilos',
        "columns":[
            {data: 'id', name: 'estilos.id'},
            {data: 'desc', name: 'tags.desc'},
            {data: 'descripcion', name: 'estilos.descripcion'},
            {data: 'iconUrl', name: 'estilos.iconUrl'},
            {data: 'weight', name: 'estilos.weight'},
            {data: 'opacity', name: 'estilos.opacity'},
            {data: 'color', name: 'estilos.color'},
            {data: 'dashArray', name: 'estilos.dashArray'},
            {data: 'fillOpacity', name: 'estilos.fillOpacity'},
            {data: 'fillColor', name: 'estilos.fillColor'},
            {data: 'action', name: 'action' , orderable: false, searchable: false}
        ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        }
    });

    $("#ocultar").fadeTo(8000, 500).slideUp(500, function(){
        $("ocultar").alert('close');
    });
});
</script>

@stop