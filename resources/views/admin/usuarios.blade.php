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
                        <a class="btn btn-success" href="/admin/usuarios/registrar" style="float: right;">
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
                        <table class="table-striped table-bordered" width="100%" id="tabla-usuarios">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de usuario</th>
                                    <th>Mail</th>
                                    <th>Area</th>
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
            <form method="POST" action="/admin/usuarios/delete" accept-charset="UTF-8" class="form-horizontal">
                <div class="modal-header" style="background: #4682B4; color: #FFFFFF;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="titulo"> Eliminar usuario</h4>
                </div>
                <div class="modal-body">
                    <p class="help-block">¿Esta seguro que desea eliminar permanentemente este usuario?</p>
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
var table = $('#tabla-usuarios').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '/datatables/usuarios',
        "columns":[
            {data: 'id', name: 'users.id'},
            {data: 'name', name: 'users.name'},
            {data: 'email', name: 'users.email'},
            {data: 'area', name: 'users.area'},
            {data: 'estado', name: 'users_info.estado'},
            {data: 'action', name: 'action' , orderable: false, searchable: false}
        ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        }
    });

    $("#ocultar").fadeTo(8000, 500).slideUp(500, function(){
        $("ocultar").alert('close');
    });

    $('#tabla-usuarios').on('draw.dt', function () {
        $(".delete").click(function(){
            $('#delete').modal();
            var id = $(this).data('id');
            $("#titulo").html(" Eliminar usuario "+$(this).closest("tr").children("td").eq(1).html());
            $(".id").val(id);
        });
    });

</script>

@stop