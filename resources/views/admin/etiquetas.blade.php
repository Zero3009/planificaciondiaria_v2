@extends('layouts.administracion')

@section('main-content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                    <div class="col-md-4" style="float: left;">
                        <h3 class="panel-title" style="margin-top: 10px;">Gestionar usuarios</h3>
                    </div>

                    <div class="col-md-8" style="float: right;">
                        <a class="btn btn-success" href="/admin/etiquetas/nueva" style="float: right;">
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
                        <table class="table-striped table-bordered" width="100%" id="tabla-etiquetas">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Grupo</th>
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
var table = $('#tabla-etiquetas').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '/datatables/etiquetas',
        "columns":[
            {data: 'id_tag', name: 'tags.id_tag'},
            {data: 'desc', name: 'tags.desc'},
            {data: 'grupo', name: 'tags.grupo'},
            {data: 'estado', name: 'tags.estado'},
            {data: 'action', name: 'action' , orderable: false, searchable: false}
        ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        }
    });

    $("#ocultar").fadeTo(8000, 500).slideUp(500, function(){
        $("ocultar").alert('close');
    });

</script>

@stop