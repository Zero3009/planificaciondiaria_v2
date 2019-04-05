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

    <form method="POST" action="/admin/areasconfig/editar/post" accept-charset="UTF-8" class="form-horizontal">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;"><h4 class="panel-title">Editar Area</h4></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Area:</label>
                            <div class="col-sm-4">
                                <input class="form-control" style="width: 100%" name="area" type="text" required value="{{$areaconfig->area}}" >
                            </div>
                            <label class="control-label col-sm-2">Secretaria:</label>
                            <div class="col-sm-4">
                                <select name="secretaria" id="secretaria" class="form-control" style="width: 100%" required>
                                    <option value="{{$areaconfig->id_secretaria}}" selected>{{$areaconfig->secretaria}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Dirección general:</label>
                            <div class="col-sm-4">
                                <select name="direccion" id="direccion" class="form-control" style="width: 100%" required>
                                    <option value="{{$areaconfig->id_direccion}}" selected>{{$areaconfig->direccion}}</option>
                                </select>
                            </div>
                            <label class="control-label col-sm-2">Descripción personalizada:</label>
                            <div class="col-sm-4">
                                <select data-placeholder=" " name="desc_personalizada[]" class="chosen-descripcion form-control" multiple>
                                @if (isset($areaconfig->campo_descripcion))
                                    @foreach(explode(',', $areaconfig->campo_descripcion) as $campo_desc)
                                        @if ($campo_desc != "")
                                            <option value="{{$campo_desc}}" selected>{{$campo_desc}}</option>
                                        @endif
                                    @endforeach
                                 @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Equipos de trabajo:</label>
                            <div class="col-sm-10">
                                <select name="equipos[]" id="equipo" data-placeholder=" " multiple class="form-control" style="width: 100%" required>
                                        @foreach($equipos as $equipo)
                                            <option value="{{ $equipo->id_equipo }}"
                                                @foreach($areaconfig->equipo_area as $equipo_area)
                                                    @if ($areaconfig->id_area == $equipo_area->pivot->id_area && $equipo_area->pivot->id_equipo == $equipo->id_equipo)
                                                        selected
                                                    @endif
                                                @endforeach
                                                >{{ $equipo->descripcion }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading active" role="tab" id="headingOne" style="background: #4682B4; color: #FFFFFF;">
                                  <h4>
                                    <span role="button" class="glyphicon glyphicon-pencil clickable_space" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                      Datos complementarios
                                    </span>
                                  </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <table class="table-striped table-bordered" width="100%" id="table">
                                            <thead>
                                                <tr>
                                                    <th>Cuadrilla</th>
                                                    <th>Hora</th>
                                                    <th>Area</th>
                                                    <th>Tipo de trabajo</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input name="id_area" id="id_area" type="hidden" value="{{$areaconfig->id_area}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input class="btn btn btn-primary" tabindex="1" type="submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')

<script>

$("#equipo").select2({
    language: "es",
    placeholder: "Seleccione un empleado",
    allowClear: true,
    tokenSeparators: [','],
});

$.getJSON("/ajax/tags", function (json) {
    $.each(json, function(i, item) {
        if(item.grupo == "secretaria"){
            if(item.id_tag != $("#secretaria").val()){
                $('#secretaria').append('<option value="'+item.id_tag+'">'+item.desc+'</option>');
            }
        }
        if(item.grupo == "direccion"){
            if(item.id_tag != $("#direccion").val()){
                $('#direccion').append('<option value="'+item.id_tag+'">'+item.desc+'</option>');
            }
        }
    });
});

function handleClick(cb){
    if(cb.checked){
        cb.value = cb.id;
    } else{
        cb.value = "";
    }
}   

$.getJSON("/ajax/roles", function (json) {
    $("#roles").select2({
        data: json,
        language: "es",
        placeholder: "Seleccionar roles"
    });
});

jQuery(document).ready(function () {
    'use strict';
    var input = 1,
        button = ("<button class='add'>Add Field</button>");
    var blank_line = $('.input_line').clone();
    $('#add').click(function () {

        $('form').append(blank_line.clone())
        $('.input_line').last().before($(this));
    });

    $('form').on('click', '.remove', function () {
        $(this).parent().remove();
         $('.input_line').last().before($('#add'));
        input = input - 1;
    });

    $('form').on('click', '.duplicate', function () {
       $('form').append($(this).parent().clone());
          $('.input_line').last().before($('#add'));
        input = input + 1;
    });
});

$(function(){
    var table =  $('#table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '/datatables/datoscomplementarios/' + $('#id_area').val(),
            "columns":[
                {data: 'id', name: 'id'},
                {data: 'desc_corta', name: 'desc_corta'},
                {data: 'desc_larga', name: 'desc_larga'},
                {data: 'html', name: 'html', render: $.fn.dataTable.render.text()},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "columnDefs": [
                {   
                    className: "id",
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            "language":{
                url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
            }
    });
    
});
</script>


@stop