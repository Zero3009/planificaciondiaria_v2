@extends('layouts.visualizador')

@section('content')

<div class="loading" style="display: none;">Loading&#8230;</div>
<div id="map" class="leaflet-container leaflet-fade-anim"></div>

<input type="hidden" value="" id="coordenadaX">
<input type="hidden" value="" id="coordenadaY">
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

<input type="hidden" name="rol" id="rol" value="@if(Auth::check()) @if (Auth::user()->is('developer')) true @endif @endif">



<div class="modal fade" id="tabla" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #00ACEC; color: #FFFFFF;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tabla de datos</h4>
            </div>
            <div class="modal-body">
                <form method="POST" accept-charset="UTF-8" class="form-horizontal">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Filtrar datos</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group"> 
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Fecha desde:</label>
                                <div class="col-md-8">     
                                    <input placeholder="Desde:" required value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="min" readonly="true">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Hasta:</label>
                                <div class="col-md-8">     
                                    <input placeholder="Hasta:" required value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="max" readonly="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Area:</label>
                                <div class="col-md-8">     
                                    <select name="area" class="form-control" id="area">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Tipo de trabajo:</label>  
                                <div class="col-md-8">     
                                    <select class="form-control" name="tipo_trabajo" id="tipo_trabajo">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group asd">
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Calle/Zona:</label>
                                <div class="col-md-8">        
                                    <input type="text" class="form-control" name="calle_zona" id="calle_zona">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label col-sm-4">Corte de calzada:</label>
                                <div class="col-md-8">        
                                    <select class="form-control" name="corte_calzada" id="corte_calzada">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6" id="botones">
                                <label class="control-label col-sm-4">Descripción:</label>
                                <div class="col-md-8">       
                                    <input type="text" class="form-control" name="descripcion" id="descripcion"> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6"><button id="buscar" class="btn btn-success" style="width: 100%"><i class="glyphicon glyphicon-filter"></i> Buscar</button></div>
                                <div class="col-md-6"><button id="limpiar" class="btn btn-default" style="width: 100%"><i class="glyphicon glyphicon-erase"></i> Limpiar</button></div>
                            </div>
                        </div>
                        

                        <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                        Ver datos complementarios <b>+</b></a>
                        </h4>
                        <div id="collapse3" class="panel-collapse collapse" style="margin-top: 10px;">
                            <div id="datos_complementarios">
                                @if (isset($datos_complementarios))
                                    @foreach($datos_complementarios->chunk(2) as $items)
                                    <div class="form-group">
                                        @foreach($items as $index => $dato_complementario)
                                            <div class="col-md-6">
                                                <span class="control-label col-sm-4"><b>{{$dato_complementario->desc_larga}}</b></span>
                                                <div class="col-sm-8">
                                                    {!!$dato_complementario->html!!}
                                                </div>
                                            </div>  
                                        @endforeach
                                    </div>
                                    @endforeach
                                @endif
                                
                            </div>
                        </div>

                    </div
 >               </div>
                </form>
                <div class="table-responsive">
                <table class="table table-striped table-bordered dataTable" id="tabla-geometrias" style="width:100%;">
                    <thead style="width:100%;">
                        <tr>
                            <th>ID</th>
                            <th>Area</th>
                            <th>Descripcion</th>
                            <th>Tipo de trabajo</th>
                            <th>Horario</th>
                            <th>Calle/Zona</th>
                            <th>Tipo de figura</th>
                            <th>Datos complementarios</th>
                            <th>Corte de calzada</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="dats">
        
</div>   
<script>
    $(document).ready( function () {
        var querydt;
        //Predefinimos variables
        EPSG900913 = new L.Proj.CRS('EPSG:900913','+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs');
        EPSG22185 = new L.Proj.CRS('EPSG:22185', '+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');

        var leyenda = new Array();
        var currentTime = new Date();

        //Variable que contiene el mapa
        map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, minZoom: 12}),

        //Setiar limites
        map.setMaxBounds([
            [-33.066801, -60.856018],
            [-32.817864, -60.493469]
        ]);
        rolAdminCheck= $("#rol").val(); 
        rolAdminCheck = rolAdminCheck.replace(/\s+/g, '');
        if(rolAdminCheck == 'true'){
            var botonesExport =[
                {
                    text: 'Exportar a GG',
                    action: function ()
                    {
                        $.getJSON('/visualizador/getgg',
                            {
                                "gg": querydt
                            },
                             function (gg) {
                            console.log(gg);
                        });
                    }
                },
                {   
                    text: 'Exportar a geojson',
                    action: function ( e, dt, node, config ) {
                        var geojson = [];
                        importItems.eachLayer(function (layer) {
                            layergeo = layer.toGeoJSON().features;
                            $.each( layergeo, function( key, value ) {
                                geojson.push(value);
                            });
                        });
                        var featureCollection = {
                            "type": "FeatureCollection",
                            "features": geojson
                        };
                        var fileName = 'Exportacion personalizada '+"<?php echo \Carbon\Carbon::now()->format('d-m-Y');?>"+'.geojson';
                        var fileToSave = new Blob([JSON.stringify(featureCollection)], {
                            type: 'application/json',
                            name: fileName
                        });
                        saveAs(fileToSave, fileName);
                    }   
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Imprimir parte',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                    },
                    customize: function(doc){
                        var filtro_area = "";
                        if($("#area").val() != ""){
                            filtro_area = "Con filtro. Área: "+$("#area option:selected").text();
                        }
                        var cols = [];
                        cols[0] = {text: "MUNICIPALIDAD DE ROSARIO\nSecretaría de Ambiente y Espacio Público\nDirección General de Parques y Paseos", alignment: 'left', margin:[10, 10, 15, 15] };
                        cols[1] = {text: "Sistema de Planificación\n"+filtro_area, alignment: 'center', margin:[10, 10, 15, 15] };
                        cols[2] = {text: "<?php echo \Carbon\Carbon::now()->format('d-m-Y');?>", alignment: 'right', margin:[10, 10, 15, 15] };
                        var objHeader = {};
                        objHeader['columns'] = cols;
                        doc['header'] = objHeader;
                        var objFooter = {};
                        objFooter['alignment'] = 'center';
                        doc["footer"] = function(currentPage, pageCount) {
                            var footer = [
                                {
                                    text: 'Página ' + currentPage + ' de ' + pageCount,
                                    alignment: 'center',
                                    color: 'blue',
                                    margin:[0, 15, 0, 15]
                                }
                            ];
                            objFooter['columns'] = footer;
                            return objFooter;
                        };
                        doc.pageMargins = [ 20, 50, 20, 40 ];
                    },
                    title: 'Programación'
                },
                {
                    extend: 'colvis',
                    text: 'Seleccionar columnas visibles'
                }
            ]
        }else{
            var botonesExport = [
                {
                    text: 'Exportar a GG',
                    action: function ()
                    {
                        $.getJSON('/visualizador/getgg',
                            {
                                "gg": querydt
                            },
                             function (gg) {
                            console.log(gg);
                        });
                    }
                },
                {
                    extend: 'excel',
                    text: 'Exportar a excel'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Imprimir parte',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                    },
                    customize: function(doc){
                        var filtro_area = "";
                        if($("#area").val() != ""){
                            filtro_area = "Con filtro. Área: "+$("#area option:selected").text();
                        }
                        var cols = [];
                        cols[0] = {text: "MUNICIPALIDAD DE ROSARIO\nSecretaría de Ambiente y Espacio Público\nDirección General de Parques y Paseos", alignment: 'left', margin:[10, 10, 15, 15] };
                        cols[1] = {text: "Sistema de Planificación\n"+filtro_area, alignment: 'center', margin:[10, 10, 15, 15] };
                        cols[2] = {text: "<?php echo \Carbon\Carbon::now()->format('d-m-Y');?>", alignment: 'right', margin:[10, 10, 15, 15] };
                        var objHeader = {};
                        objHeader['columns'] = cols;
                        doc['header'] = objHeader;
                        var objFooter = {};
                        objFooter['alignment'] = 'center';
                        doc["footer"] = function(currentPage, pageCount) {
                            var footer = [
                                {
                                    text: 'Página ' + currentPage + ' de ' + pageCount,
                                    alignment: 'center',
                                    color: 'blue',
                                    margin:[0, 15, 0, 15]
                                }
                            ];
                            objFooter['columns'] = footer;
                            return objFooter;
                        };
                        doc.pageMargins = [ 20, 50, 20, 40 ];
                    },
                    title: 'Parte diario'
                },
                {
                    extend: 'colvis',
                    text: 'Seleccionar columnas visibles'
                }
            ]
        }
        var table = $('#tabla-geometrias').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [ [10, 50, 100, -1], [10, 50, 100, "All"] ],
            "pageLength": 2000,
            "bLengthChange": false,
            ajax: {
                url: '/datatables/geometrias',
                data: function (d) {
                    d.min = $('#min').val();
                    d.max = $('#max').val();
                    d.area = $('#area').val();
                    d.tipo_trabajo = $('#tipo_trabajo').val();
                    d.corte_calzada = $('#corte_calzada').val();
                    d.calle_zona = $('#calle_zona').val();
                    d.descripcion = $('#descripcion').val();
                    d.datos_complementarios = $('#datos_complementarios :input').serialize();
                },
                dataSrc: function (response)
                {

                    querydt = response.key1;
                    return response.data;
                }
            },
            "bFilter": false,
            "columns":[
                {data: 'id_info', name: 'planificacion_info.id_info', visible: false, orderable: false, searchable: false},
                {data: 'area', name: 'planificacion_info.area'},
                {data: 'descripcion', name: 'planificacion_info.descripcion'},
                {data: 'tipo_trabajo', name: 'planificacion_info.tipo_trabajo'},
                {data: 'horario', name: 'planificacion_info.horario'},
                {data: 'callezona', name: 'planificacion_info.callezona'},
                {data: 'tipo_geometria', name: 'planificacion_info.tipo_geometria', visible: false},
                {data: 'datos_complementarios', name: 'planificacion_info.datos_complementarios', visible: false},
                {data: 'corte_calzada', name: 'planificacion_info.corte_calzada'},
                {data: 'fecha_planificada', name: 'planificacion_info.fecha_planificada'},
                {data: 'action', name: 'action' , orderable: false, searchable: false},
                {
                    data: null,
                    visible: false,
                    className: "center",
                    defaultContent: ''
                }
            ],
            "language":{
                url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
            },
            drawCallback: function() {
                cargarJson();

            },
            dom: 'Bfrtip',
            buttons: [
                botonesExport
            ],
        });

        //Crear grupos
        importItems = L.featureGroup().addTo(map);
        referencias = L.featureGroup().addTo(map);

        //Añadimos controles
        controles = L.control.layers({
            "OSM Proxy": L.tileLayer.wms('http://pyp-svr.pyp.rosario.gov.ar/mapproxy/service?', {
                layers: ['osm'],
                format: 'image/jpeg',
                crs: EPSG900913
            }).addTo(map),
            "INFOMAPA": L.tileLayer.wms('http://infomapa.rosario.gov.ar/wms/planobase?', {
                layers: ['rural_metropolitana','manzanas_metropolitana','limites_metropolitana','limite_municipio','sin_manzanas','manzanas','parcelas','manzanas_no_regularizada','espacios_verdes','canteros','av_circunvalacion','avenidas_y_boulevares','sentidos_de_calle','via_ferroviaria','hidrografia','puentes','islas_del_parana','bancos_de_arena','autopistas','nombres_de_calles','numeracion_de_calles'],
                format: 'image/jpeg',
                crs: EPSG22185,
                attribution: '&copy; Municipalidad de Rosario'
            }),
            "OSM": L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors'}),
            "GMap Satelital": L.tileLayer('http://www.google.com/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
                attribution: 'Google'
            }),
        },
        {'Capa':importItems}, { position: 'topright', collapsed: true }).addTo(map);

        function onEachCapasUtiles(feature, layer, id) {
            if(feature.properties.label){
                layer.bindTooltip(feature.properties.label.toString());
            }
            else if (feature.properties.id){
                layer.bindTooltip(feature.properties.id.toString());
            }
        }

        $.getJSON("/ajax/capasutiles", function (json) {
            var capasutiles = [];
            $.each(json, function(i, item) {
                geojson = jQuery.parseJSON(item.geojson);
                capasutiles[i] = L.featureGroup().addTo(map);
                if(geojson.features != null){
                    L.geoJSON(geojson, {
                        onEachFeature: onEachCapasUtiles, 
                        style: function(feature) {
                            return { 
                                color: item.color,
                                dashArray: item.dashArray,
                                fillColor: item.fillColor,
                                fillOpacity: item.fillOpacity,
                                opacity: item.opacity,
                                weight: item.weight
                            };
                        }
                    }).addTo(capasutiles[i]);
                    controles.addOverlay(capasutiles[i], item.nombre);
                    map.removeLayer(capasutiles[i]);
                }
                else {
                    console.log("No se encontraron polylineas");
                }
            });
        });

        //Estilo default
        var default_estilo_point = L.icon({
            iconUrl: '/plugins/leaflet/images/marker-icon-2x-blue.png',
            shadowUrl: '/plugins/leaflet/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.easyButton('glyphicon glyphicon-th-list', function(btn, map){
            $("#tabla").modal();
        }).addTo(map);

        L.browserPrint({
            title: 'Sistema de Planificación Diaria',
            printModes: ["Portrait", "Landscape", "Auto", "Custom"]
        }).addTo(map);

        $(function() {
            var currentTime = new Date();
            if(rolAdminCheck == 'true'){
                var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth() -11,1);
            }
            else{
                var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth() -2,1);   
            }

            $("#min").datepicker({
                numberOfMonths: 1,   
                showAnim: "slideDown",
                dateFormat: "yy-mm-dd",
                minDate: startDateFrom,
                onClose: function(selectedDate) {
                    $("#max").datepicker("option", "minDate", selectedDate);
                }
            });
            $("#max").datepicker({
                numberOfMonths: 1,      
                showAnim: "slideDown",
                dateFormat: "yy-mm-dd",
                maxDate: '0',
                onClose: function(selectedDate) {
                    $("#min").datepicker("option", "maxDate", selectedDate);
                }
            });
        });

        //BOTON BUSCAR PARAMETROS DETERMINADOS
        $("#buscar").click(function(e){
            e.preventDefault();

            if($("#min").val() && $("#max").val()){
                table.draw();
            }
            else if ($("#min").val() == "" && $("#min").val() == ""){
                table.draw();
            } 
            else{
                alert("Elija la fecha correctamente");
            }
        });

        // BOTON LIMPIAR PARAMETROS Y REBUSCAR DEFAULT
        $("#limpiar").click(function(e){
            e.preventDefault();
            $("#min").val("<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>");
            $("#min").val("<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>");
            $("#corte_calzada").val("");
            $("#tipo_trabajo").val("");
            $("#area").val("");
            $("#descripcion").val("");
            $("#calle_zona").val("");
            $('#datos_complementarios').find('input').val('');
            $("#buscar").click();
        });

        //BOTON LIMPIAR REFERENCIAS
        $("#limpiar-puntos").click(function(){
            referencias.clearLayers();
            $("#txtDireccionesLugares").val("");
        });

        $('#tabla-geometrias').on('draw.dt', function () {
            $(".ubicar").click(function(e){
                id = $(this).data("id");
                map.eachLayer(function (layer) {
                    if(layer.feature){
                        if(layer.feature.properties.id_info == id){
                            if(layer.feature.geometry.type == "Point"){
                                map.setView(layer.getLatLng(), 16);
                                $('#tabla').modal('hide');
                                layer.openPopup();
                            }
                            else if (layer.feature.geometry.type == "Polygon" || layer.feature.geometry.type == "LineString"){
                                map.fitBounds(layer.getBounds());
                                $('#tabla').modal('hide');
                                layer.openPopup();
                            }
                        }
                    }
                });
            });
            $(".dats").click(function(e){
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = row.data().id_info;
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    format(row.child, tableId);
                    tr.addClass('shown');
                }
            });
        });

        $.getJSON("/ajax/tags", function (json) {
            $.each(json, function(i, item) {
                if(item.grupo == "area"){
                    $('#area').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
                }
                else if (item.grupo == "corte_calzada"){
                    $('#corte_calzada').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
                } 
                else if (item.grupo == "tipo_trabajo"){
                    $('#tipo_trabajo').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
                }
            });
        });

        var legendControl = L.Control.extend({
            options: {
                position: 'bottomright'
            },
            onAdd: function (map) {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control legend');
                container.style.backgroundColor = 'white';
                container.style.padding = '5px';
                container.innerHTML += "<h5 style='text-align: center;font-weight: bold;'><span>REFERENCIAS</span></h5>";
                $.getJSON("/ajax/legend",function(data){
                    $.each(data, function( key, value ) {
                        container.innerHTML += "<div><i style='background:"+value.color+";width: 18px;height: 18px;float: left;margin-right: 8px;'></i><span> "+value.desc+"</span></div><br>";
                    });
                });
                return container;
            },
        });
        map.addControl(new legendControl());


        function onEachFeature(feature, layer, id) {
            var sololectura =
                '<span><b>Calle / Zona</b></span>'+
                '<p>'+feature.properties.callezona+'</p>'+
                '<span><b>Fecha</b></span>'+
                '<p>'+feature.properties.created_at+'</p>'+
                '<span><b>Descripción</b></span>'+
                '<p>'+feature.properties.descripcion+'</p>'+
                '<span><b>Tipo de trabajo</b></span>'+
                '<p>'+feature.properties.tipo_trabajo+'</p>'+
                '<span><b>Horario</b></span>'+
                '<p>'+feature.properties.horario+'</p>'+
                '<span><b>Corte de calzada</b></span>'+
                '<p>'+feature.properties.corte_calzada+'</p>';
                for(var p in feature.properties.datos_complementarios){
                    if(feature.properties.datos_complementarios[p] != ""){
                        for(var i = 0;i < globalData.length; i++)
                        {
                            if(globalData[i].desc_corta == p)
                            {
                                sololectura = sololectura + '<span><b>'+ globalData[i].desc_larga +'</b></span>'+'<p>'+ feature.properties.datos_complementarios[p] +'</p>';
                                break;
                            }
                            
                        }
                    }
                }
            layer.bindPopup(sololectura);
        }
        var globalData;
        function cargarJson(){
            $.getJSON("/ajax/datos_complementarios_desclarga", function(data){
                globalData = data;
            });
            id_info = $('#tabla-geometrias').DataTable().columns([0,6]).data();
            importItems.clearLayers();
           
            $.getJSON("/ajax/estilo_capa", function (estilos) {
                var puntos = new Array();
                var poligonos = new Array();
                var trazas = new Array();
                for ($i=0; $i < id_info[0].length; $i++){
                    if(id_info[1][$i] == "Point"){
                        puntos.push(id_info[0][$i]);
                    }
                    else if (id_info[1][$i] == "Polygon")
                    {
                        poligonos.push(id_info[0][$i]);
                    }
                    else if (id_info[1][$i] == "LineString")
                    {
                        trazas.push(id_info[0][$i]);
                    }
                }   
                ids_puntos = puntos.toString();
                ids_poligonos = poligonos.toString();
                ids_lineas = trazas.toString();

                if(ids_lineas){
                    $.ajax({
                        type:"POST",
                        url:'/ajax/lineas',
                        data: {"ids_info": ids_lineas, "_token": $("#_token").val(), "origen": "visualizador" },
                        dataType: 'json',
                        success: function(response) {
                            geojson = jQuery.parseJSON(response[0].row_to_json);
                            console.log(geojson);
                            if(geojson.features != null){
                                L.geoJSON(geojson, { 
                                    onEachFeature: onEachFeature, 
                                    style: function(feature) {
                                        return {
                                            weight: estilos[feature.properties.id_area].weight,
                                            opacity: estilos[feature.properties.id_area].opacity,
                                            color: estilos[feature.properties.id_area].color,
                                            dashArray: estilos[feature.properties.id_area].dashArray,
                                            fillOpacity: estilos[feature.properties.id_area].fillOpacity,
                                            fillColor: estilos[feature.properties.id_area].fillColor
                                        };
                                    }, 
                                }).addTo(importItems);
                            }
                            else {
                                console.log("No se encontraron polylineas");
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
                if(ids_poligonos){
                    $.ajax({
                        type:"POST",
                        url:'/ajax/poligonos',
                        data: {"ids_info": ids_poligonos, "_token": $("#_token").val(), "origen": "visualizador" },
                        dataType: 'json',
                        success: function(response) {
                            geojson = jQuery.parseJSON(response[0].row_to_json);
                            console.log(geojson);
                            if(geojson.features != null){
                                L.geoJSON(geojson, { 
                                    onEachFeature: onEachFeature, 
                                    style: function(feature) {
                                        return {
                                            weight: estilos[feature.properties.id_area].weight,
                                            opacity: estilos[feature.properties.id_area].opacity,
                                            color: estilos[feature.properties.id_area].color,
                                            dashArray: estilos[feature.properties.id_area].dashArray,
                                            fillOpacity: estilos[feature.properties.id_area].fillOpacity,
                                            fillColor: estilos[feature.properties.id_area].fillColor
                                        };
                                    }, 
                                }).addTo(importItems);
                            }
                            else {
                                console.log("No se encontraron poligonos");
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
                if(ids_puntos){
                    $.ajax({
                        type:"POST",
                        url:'/ajax/puntos',
                        data: {"ids_info": ids_puntos, "_token": $("#_token").val(), "origen": "visualizador"},
                        dataType: 'json',
                        success: function(response) {
                            geojson = jQuery.parseJSON(response[0].row_to_json);
                            console.log(geojson);
                            if(geojson.features != null){
                                L.geoJSON(geojson, { 
                                    onEachFeature: onEachFeature, 
                                    pointToLayer: function(feature, latlng) {
                                        estilo_punto = L.icon({
                                                iconUrl: estilos[feature.properties.id_area].iconUrl,
                                                iconSize: [25, 41],
                                                iconAnchor: [12, 41],
                                                popupAnchor: [1, -34],
                                                shadowSize: [41, 41]
                                            });
                                        return L.marker(latlng, {icon: estilo_punto});
                                    }
                                }).addTo(importItems);
                            }
                            else {
                                console.log("No se encontraron puntos");
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            }); 
        }

        //API de ubicaciones de la MR
        $("#txtDireccionesLugares").ubicaciones({
            filtro:{
                clase:'ubicacion', //Para filtrar lugares y direcciones
                filtroclase: {
                    tipo: 'all',//'10',    //Para filtrar tipos de lugares de salud
                    subtipo: 'all'//'22'  //Para filtrar x subtipo hospitales
                },
                extendido:false,
                callback: function(resultado) 
                {          
                    if (resultado.properties.subtipo == "CALLE"){
                        $(".loading").css("display","block");
                        var calles = resultado.properties.id;
                        var offset = 0;
                        geojson_poligonos = new Array();
                        features = new Array();
                        getJsonCalles(offset, calles);
                        setTimeout(function(){ 
                            $.each( geojson_poligonos, function( key, value ) {
                                $.each(value.records, function( key2, value2 ) {
                                    features.push(jQuery.parseJSON(value2.GEOJSON));
                                });
                            });
                            var featureCollection = {
                                "type": "FeatureCollection",
                                "features": features
                            };
                            var linesFeatureLayer = L.geoJson(featureCollection);
                            linesFeatureLayer.addTo(referencias);
                            map.fitBounds(linesFeatureLayer.getBounds());
                            
                            $("#txtDireccionesLugares-ul").css("display","none");
                            $(".loading").css("display","none");

                        }, 1000);
                    }
                    else{
                        $('#coordenadaX').val(
                            resultado.geometry
                            ? resultado.geometry.coordinates[0]
                            : ''
                        );
                        $('#coordenadaY').val(
                            resultado.geometry
                            ? resultado.geometry.coordinates[1]
                            : ''
                        );
                        agregarpuntos(resultado.geometry.coordinates[0], resultado.geometry.coordinates[1]);
                        $("#txtDireccionesLugares").val("");
                    }
                },
                url: 'https://ws.rosario.gob.ar/ubicaciones/public/geojson/ubicaciones',
                mostrarReferenciasAlturas:true,
                urlReferenciasAlturas:'https://ws.rosario.gob.ar/ubicaciones/public/referenciaalturas',
            },
            minLength: 4,
            pathImg: '/img',
            sinBotonBusqueda: true,
        });

        function getJsonCalles(offset, calles){
            var data = {
                resource_id: 'fb0b081e-572e-4d80-8c89-1b9c957a349f',
                filters: {"CODIGO": calles},
                "fields[]": 'GEOJSON',
                limit: 100,
                offset: offset
            };
            $.ajax({ 
                url: 'http://datos.rosario.gob.ar/api/action/datastore/search.jsonp',
                data: data,
                async: false,
                dataType: 'jsonp',
                success: function(geojson) {
                    if(geojson.result.limit == 100){
                        offset = offset+100;
                        getJsonCalles(offset, calles);
                    }
                    geojson_poligonos.push(geojson.result);
                }
            });
        }

        //Agregar puntos desde la API y convertirlos
        function agregarpuntos(coordenadaX, coordenadaY){
            var firstProjection = "+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
            var secondProjection = "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs ";
            conversion = proj4(firstProjection,secondProjection,[coordenadaX,coordenadaY]);

            var marker = L.marker([conversion[1], conversion[0]],{icon: L.icon({
                    iconUrl: '/plugins/leaflet/images/info.png',
                    iconSize: [20, 20]
            })}).addTo(referencias);
            map.setView([conversion[1], conversion[0]], 16);
        }
        $("#tabla-geometrias tbody").on("click", "td.details-control", function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = row.data().id_master;
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                format(row.child, tableId);
                tr.addClass('shown');
            }
        });
        function format(callback, $tableId) {
            $.ajax({
                url: "/ajax/visualizadordetails/" + $tableId,
                //dataType: "json",
                beforeSend: function(){
                    callback($('<div align="center">Cargando...</div>')).show();
                },
                complete: function (response) {
                    var data = JSON.parse(response.responseText);
                    var tbody = "";

                    var thead = '',  tbody = '';
                        thead += '<th>Dato</th>'; 
                        thead += '<th>Valor</th>'; 

                    $.each(data, function(i, item) {
                        for(var k = 0;k < globalData.length; k++)
                        {
                            if(globalData[k].desc_corta == i)
                            {
                        
                                tbody += '<tr><td>'+ globalData[k].desc_larga +'</td><td>'+ item +'</td></tr>';
                                break;
                            }
                        }
                    });

                    callback($('<div class="panel panel-default" style="width: 70%;margin: auto;"><div class="panel-heading"><h3 class="panel-title"><strong>Datos complementarios</strong></h3></div><div class="panel-body"><table class="table">' + thead + tbody + '</table></div></div>')).show();
                },
                error: function () {
                    callback($('<div align="center">Ha ocurrido un error. Intente nuevamente y si persigue el error, contactese con informática.</div>')).show();
                }
            });
        }
    });
</script>

@endsection

