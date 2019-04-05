@extends('layouts.administracion')

@section('main-content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                    <div class="col-md-4" style="float: left;">
                        <h3 class="panel-title" style="margin-top: 10px;">Gestionar areas</h3>
                    </div>

                    <div class="col-md-8" style="float: right;">
                        <a class="btn btn-success" href="/admin/areasconfig/nuevo" style="float: right;">
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
                        <table class="table-striped table-bordered" width="100%" id="tabla-areasconfig">
                            <thead>
                                <tr>
                                    <th>ID area</th>
                                    <th>Area</th>
                                    <th>Secretaria</th>
                                    <th>Dirección</th>
                                    <th>Campo personalizado descripción</th>
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

<div class="modal fade" id="delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="/admin/areasconfig/delete" accept-charset="UTF-8" class="form-horizontal">
                <div class="modal-header" style="background: #4682B4; color: #FFFFFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="titulo"> Deshabilitar area</h4>
                </div>
                <div class="modal-body">
                    <p class="help-block">¿Esta seguro que desea deshabilitar este area?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" class="id">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-success" value="Eliminar">
                    
                </div>
            </form>
        </div>
    </div>
</div>


@stop

@section('js')

<script>
var table = $('#tabla-areasconfig').DataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": '/datatables/areasconfig',
        "columns":[
            {data: 'id_area', name: 'areas_config.id_area'},
            {data: 'area', name: 'area.desc'},
            {data: 'secretaria', name: 'secretaria.desc'},
            {data: 'direccion', name: 'direccion.desc'},
            {data: 'campo_descripcion', name: 'areas_config.campo_descripcion'},
            {data: 'estado', name: 'areas_config.estado'},
            {data: 'action', name: 'action' , orderable: false, searchable: false}
        ],
        "order": [ 0, "desc" ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        }
    });

    $("#ocultar").fadeTo(8000, 500).slideUp(500, function(){
        $("ocultar").alert('close');
    });

    $('#tabla-areasconfig').on('draw.dt', function () {
        $(".delete").click(function(){
            $('#delete').modal();
            var id = $(this).data('id');
            console.log(id);
            $("#titulo").html(" Eliminar area "+$(this).closest("tr").children("td").eq(1).html());
            $(".id").val(id);
        });
    });

</script>

@stop