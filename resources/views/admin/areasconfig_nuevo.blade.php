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

    <form method="POST" action="/admin/areasconfig/nuevo/post" accept-charset="UTF-8" class="form-horizontal">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;"><h4 class="panel-title">Nueva Area</h4></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Area:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="area" type="text" required>
                            </div>
                            <label class="control-label col-sm-2">Secretaria:</label>
                            <div class="col-sm-4">
                                <select name="secretaria" id="secretaria" class="form-control" style="width: 100%" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Dirección general:</label>
                            <div class="col-sm-4">
                                <select name="direccion" id="direccion" class="form-control" style="width: 100%" required></select>
                            </div>
                            <label class="control-label col-sm-2">Descripción personalizada:</label>
                            <div class="col-sm-4">
                                <select data-placeholder=" " name="desc_personalizada[]" class="chosen-descripcion form-control" multiple></select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input name="usuario" type="hidden" value="{{Auth::user()->id}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="btn btn btn-primary" tabindex="1" type="submit" value="Crear area">
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
        if(item.grupo == "secretaria"){
            $('#secretaria').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
        }
        if(item.grupo == "direccion"){
            $('#direccion').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
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

$(".chosen-descripcion").chosen({
    create_option: true,
    persistent_create_option: true,
    skip_no_results: true
});

</script>


@stop