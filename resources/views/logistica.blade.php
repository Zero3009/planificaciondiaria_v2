@extends('layouts.visualizador')

@section('content')

<div class="loading" style="display: none;">Loading&#8230;</div>

<div class="pag-1" style="height: 100%;width:  100%;">
    <div id="titulo" class="titulo" style="display: none;">
        <h2>Sistema de Planificación y logistica</h2>
    </div>
    <div id="ruta" class="ruta" style="display: none;"></div>
    <div id="map" class="leaflet-container leaflet-fade-anim"></div>
</div>

<div class="page-break">&nbsp;</div>
<div class="pag-2" style="height: 100%;width:  100%; display:none;">
    
</div>

<div class="modal fade" id="tabla" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #00ACEC; color: #FFFFFF;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tabla de datos</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="/ordenservicio/nueva" id="form-orden" accept-charset="UTF-8" class="form-horizontal">
                    <input type="hidden" value="logistica" id="page">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="area" name="area" value="{{ Auth::user()->UserInfo->id_area }}">

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
                            <div class="form-group">
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
                                    <div class="col-md-6"><button id="buscar" class="btn btn-info" style="width: 100%"><i class="glyphicon glyphicon-filter"></i> Buscar</button></div>
                                    <div class="col-md-6"><button id="limpiar" class="btn btn-default" style="width: 100%"><i class="glyphicon glyphicon-erase"></i> Limpiar</button></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" class="btn btn-success" value="Crear orden de servicio">
                        </div>
                    </div>
                    
                    <table class="table table-striped table-bordered tabla-filtro" id="tabla-geometrias" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Seleccionar</th>
                                <th>Area</th>
                                <th>Descripcion</th>
                                <th>Tipo de trabajo</th>
                                <th>Horario</th>
                                <th>Calle/Zona</th>
                                <th>Tipo de figura</th>
                                <th>Corte de calzada</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
        
<script>
    $(document).ready( function () {

        //Predefinimos variables
        var EPSG900913 = new L.Proj.CRS('EPSG:900913','+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs');
        var EPSG22185 = new L.Proj.CRS('EPSG:22185', '+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');

        var selectedIcon = L.icon.pulse({iconSize:[5,5],color:'blue'});
        var coleccion = [];
        var idseleccionados = [];
        var bounds = null;


        //Variable que contiene el mapa
        map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, minZoom: 12}),

        //Setiar limites
        map.setMaxBounds([
            [-33.066801, -60.856018],
            [-32.817864, -60.493469]
        ]);

        table = $('#tabla-geometrias').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [ [10, 50, 100, -1], [10, 50, 100, "All"] ],
            "pageLength": 2000,
            "bLengthChange": false,
            ajax: {
                url: '/ordenservicio/listar-planificacion',
                data: function (d) {
                    d.min = $('#min').val();
                    d.max = $('#max').val();
                    d.area = $('#area').val();
                    d.page = $('#page').val();
                    d.tipo_trabajo = $('#tipo_trabajo').val();
                    d.corte_calzada = $('#corte_calzada').val();
                    d.calle_zona = $('#calle_zona').val();
                    d.idseleccionados = idseleccionados;
                }
            },
            "bFilter": false,
            "select": {
                style: 'multi'
            },
            "columns":[
                {data: 'id_info', name: 'planificacion_info.id_info', visible: false, orderable: false, searchable: false},
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0,
                    searchable: false,
                    data: null,
                    defaultContent: ''
                },
                {data: 'area', name: 'planificacion_info.area'},
                {data: 'descripcion', name: 'planificacion_info.descripcion'},
                {data: 'tipo_trabajo', name: 'planificacion_info.tipo_trabajo'},
                {data: 'horario', name: 'planificacion_info.horario'},
                {data: 'callezona', name: 'planificacion_info.callezona'},
                {data: 'tipo_geometria', name: 'planificacion_info.tipo_geometria', visible: false},
                {data: 'corte_calzada', name: 'planificacion_info.corte_calzada'},
                {data: 'fecha_planificada', name: 'planificacion_info.fecha_planificada'},
                {data: 'action', name: 'action' , orderable: false, searchable: false},
            ],
            "language":{
                url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
            },
            drawCallback: function() {
                cargarJson();
            }
        });

        $('#form-orden').on('submit', function(e){
            var form = this;
            var rows_selected = table.rows('.selected').data();
            $.each(rows_selected, function(index, rowId){
                $(form).append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'id_seleccionados[]')
                    .val(rowId.id_info)
                );
            });
        });

        //Crear grupos
        importItems = L.featureGroup().addTo(map);
        referencias = L.featureGroup().addTo(map);
        ruta = L.featureGroup().addTo(map);

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
        {'Capa':importItems}, { position: 'bottomleft', collapsed: true }).addTo(map);

        drawControl = new L.Control.Draw({
            draw: {
                polygon : true,
                polyline : false,
                rectangle : false,
                circle: false,
                marker: false,
            }
        });

        L.easyButton('glyphicon glyphicon-th-list', function(btn, map){
            $("#tabla").modal();
        }).addTo(map);

        map.addControl(drawControl);

        $(function() {
            var currentTime = new Date();
            // First Date Of the month 
            var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth() -7,1);

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
        });

        //En desarrollo (select by poligono) 
        //Funcion para obtener todos los puntos del mapa
        function getAllMarkers() {
            var allMarkersObjArray = [];
            $.each(map._layers, function (ml) {
                if (map._layers[ml].feature && map._layers[ml].feature.properties.selected == false) {
                    allMarkersObjArray.push(this);
                }
            })
            return allMarkersObjArray;
        }
        //En desarrollo (select by poligono) 
        //Funcion para determinar cuales de todos los puntos del mapa estan dentro de un poligono (el dibujado con draw)
        function getMarkersInPolygon(markers, poly) {
            var allmarkersInside = []; //new Array(); 
            $.each(markers, function(i, marker) {
                var inside = false;
                var x = marker.getLatLng().lat, y = marker.getLatLng().lng;
                for (var ii=0;ii<poly.getLatLngs().length;ii++){
                    var polyPoints = poly.getLatLngs()[ii];
                    for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
                        var xi = polyPoints[i].lat, yi = polyPoints[i].lng;
                        var xj = polyPoints[j].lat, yj = polyPoints[j].lng;

                        var intersect = ((yi > y) != (yj > y))
                            && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                        if (intersect) inside = !inside;
                    }
                    if(inside == true){
                        allmarkersInside.push(marker);
                    }
                }
            });
            return allmarkersInside;  
        };

        //En desarrollo (select by poligono)    
        map.on('draw:created', function(event) {
            allMarkers = getAllMarkers();
            MarkersInPolygon = getMarkersInPolygon(allMarkers, event.layer);
            $("#tabla").modal();
            $.each(MarkersInPolygon, function(i, item) {
                console.log(item.feature.properties.id_info);
                table.row("#"+item.feature.properties.id_info).select();

            });
        });

        map.on('draw:drawstart', function(event) {
            table.draw();
        });

        $.getJSON("/ajax/tags", function (json) {
            $.each(json, function(i, item) {
                if (item.grupo == "corte_calzada"){
                    $('#corte_calzada').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
                } 
                else if (item.grupo == "tipo_trabajo"){
                    $('#tipo_trabajo').append('<option value="'+item.id_tag+'"">'+item.desc+'</option>');
                }
            });
        });

        var selected = L.icon({
            iconUrl: "/plugins/leaflet/images/marker-icon-2x-red.png",
            iconSize: [40, 61],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var deselected = L.icon({
            iconUrl: "/plugins/leaflet/images/marker-icon-2x-red.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

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
            
            feature.properties.selected = false;
            layer.bindPopup(sololectura);
        }

        function cargarJson(){
            id_info = $('#tabla-geometrias').DataTable().columns([0,7]).data();
            importItems.clearLayers();
            console.log(id_info);

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

           /* if(ids_lineas){
                $.ajax({
                    type:"POST",
                    url:'/ajax/lineas',
                    data: {"ids_info": ids_lineas, "_token": $("#_token").val(), "origen": "visualizador", "rol": "area", "area": $('#area').val()},
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, { 
                                onEachFeature: onEachFeature, 
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
                    data: {"ids_info": ids_poligonos, "_token": $("#_token").val(), "origen": "visualizador", "area": $('#area').val() },
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, { 
                                onEachFeature: onEachFeature, 
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
            }*/
            if(ids_puntos){
                $.ajax({
                    type:"POST",
                    url:'/ajax/puntos',
                    data: {"ids_info": ids_puntos, "_token": $("#_token").val(), "origen": "visualizador", "area": $('#area').val()},
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, { 
                                onEachFeature: onEachFeature
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
        }
    });
</script>

@endsection

