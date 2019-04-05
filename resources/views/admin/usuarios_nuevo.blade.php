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

    <form method="POST" action="/admin/usuarios/nuevo" accept-charset="UTF-8" class="form-horizontal">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;"><h4 class="panel-title">Nuevo usuario</h4></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nombre de usuario:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="name" type="text" required>
                            </div>
                            <label class="control-label col-sm-2">E-Mail:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="email" type="text" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Roles asignados:</label>
                            <div class="col-sm-4">
                                <select name="roles[]" id="roles" style="width: 100%" required></select>
                            </div>
                            <label class="control-label col-sm-2">Area:</label>
                            <div class="col-sm-4">
                                <select name="area" id="area" class="form-control" style="width: 100%" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Password:</label>
                            <div class="col-sm-4 has-feedback">
                                <input type="password" class="form-control" name="password"/>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <label class="control-label col-sm-2">Repita password:</label>
                            <div class="col-sm-4 has-feedback">
                                <input type="password" class="form-control" name="password_confirmation"/>
                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input name="usuario" type="hidden" value="{{Auth::user()->id}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="btn btn btn-primary" tabindex="1" type="submit" value="Crear usuario">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')

<script>

$.getJSON("/ajax/tags", function (json) {
    $.each(json, function(i, item) {
        if(item.grupo == "area"){
            $('#area').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
        }
    });
});

$.getJSON("/ajax/roles", function (json) {
    $("#roles").select2({
        data: json,
        language: "es",
        placeholder: "Seleccionar roles"
    });
});

</script>


@stop