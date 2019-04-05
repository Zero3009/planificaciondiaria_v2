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
                        <a class="btn btn-success" href="/admin/capasutiles/nuevo" style="float: right;">
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
                        <table class="table-striped table-bordered" width="100%" id="tabla-areasutiles">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>iconUrl</th>
                                    <th>Weight</th>
                                    <th>Opacity</th>
                                    <th>Color</th>
                                    <th>DashArray</th>
                                    <th>FillOpacity</th>
                                    <th>fillColor</th>
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
            <form method="POST" action="/admin/capasutiles/delete" accept-charset="UTF-8" class="form-horizontal">
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
var table = $('#tabla-areasutiles').DataTable({
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": '/datatables/capasutiles',
        "columns":[
            {data: 'id', name: 'id'},
            {data: 'nombre', name: 'nombre'},
            {data: 'iconUrl', name: 'iconUrl'},
            {data: 'weight', name: 'weight'},
            {data: 'opacity', name: 'opacity'},
            {data: 'color', name: 'color'},
            {data: 'dashArray', name: 'dashArray'},
            {data: 'fillOpacity', name: 'fillOpacity'},
            {data: 'fillColor', name: 'fillColor'},
            {data: 'estado', name: 'estado'},
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