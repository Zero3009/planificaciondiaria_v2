@extends('layouts.administracion')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                    <div class="col-md-4" style="float: left;">
                        <h3 class="panel-title" style="margin-top: 10px;">Gestionar equipo</h3>
                    </div>

                    <div class="col-md-8" style="float: right;">
                        <a class="btn btn-success" href="/admin/equipo/nuevo" style="float: right;">
                        <i class="fa fa-plus"></i> Nuevo</a>
                    </div>
                </div>
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
                        <table class="table-striped table-bordered" width="100%" id="tabla-equipo">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
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

var table = $('#tabla-equipo').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '/datatables/equipo',
        "columns":[
            {data: 'id_equipo', name: 'id_equipo'},
            {data: 'descripcion', name: 'descripcion'},
            {data: 'estado', name: 'estado'},
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