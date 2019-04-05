@extends('layouts.administracion')

@section('main-content')

    <!-- Mensajes de error-->

    @if($errors->has())
        <div class="alert alert-warning" role="alert" id="ocultar">
           @foreach ($errors->all() as $error)
              <div>{{ $error }}</div>
          @endforeach
        </div>
    @endif 

    <form method="POST" action="/admin/usuarios/editar" accept-charset="UTF-8" class="form-horizontal">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;"><h4 class="panel-title">Modificar usuario</h4></div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nombre de usuario:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="name" type="text" value="{{$user->name}}">
                            </div>
                            <label class="control-label col-sm-2">E-Mail:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="email" type="text" value="{{$user->email}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Roles asignados:</label>
                            <div class="col-sm-4">
                                <select name="roles[]" id="roles" style="width: 100%" required></select>
                            </div>
                            <label class="control-label col-md-2">Area:</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="area" id="area" required>
                                    <option value="{{$user->id_area}}" class="areaactiva" active>{{$user->area}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Contraseña nueva:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="passNuevo" type=password "text" value="">
                            </div>
                            <label class="control-label col-sm-2">Confirmar contraseña nueva:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="passNuevoRep" type=password "text" value="">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input name="id_user" type="hidden" value="{{$user->id}}">
                        <input name="usuario" type="hidden" value="{{Auth::user()->id}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="btn btn btn-primary" tabindex="1" type="submit" value="Modificar">
                    </div>
                </div>
            </div>
        </div>
    </form>

@stop

@section('js')

<script>    

$.getJSON("/ajax/roles", function (json) {
    select2roles = $("#roles").select2({
        data: json,
        language: "es",
        placeholder: "Seleccionar roles",
    });
    roles = {!! json_encode($user->roles->toArray()) !!};
    array = new Array();
    jQuery.each( roles, function( i, val ) {
        array.push(val.id);
    });
    select2roles.val(array).trigger("change");
});

$.getJSON("/ajax/tags", function (json) {
    $.each(json, function(i, item) {
        if(item.grupo == "area"){
            if(item.id_tag != $(".areaactiva").val()){
                $('#area').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
            }
        }
    });
});

</script>

@stop