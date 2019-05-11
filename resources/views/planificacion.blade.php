@extends('layouts.app')

@section('template_linked_css')

<link rel="stylesheet" href="/ubicaciones.css" />
<link rel="stylesheet" href="/plugins/leaflet/leaflet.css" />
<link rel="stylesheet" href="/plugins/leaflet/leaflet.draw.css" />
<link rel="stylesheet" href="/plugins/leaflet/easy-button.css" />
<link rel="stylesheet" href="/plugins/leaflet-icon-pulse-master/L.Icon.Pulse.css" />
<link rel="stylesheet" href="/estilos.css" />
<link rel="stylesheet" href="/plugins/select2-4.0.3/css/select2.min.css" />
<link rel="stylesheet" href="/css/mySwitch.css">
<link rel="stylesheet" href="/plugins/Leaflet.markercluster/MarkerCluster.css" />
<link rel="stylesheet" href="/plugins/Leaflet.markercluster/MarkerCluster.Default.css" />
<link rel="stylesheet" href="/plugins/chosen/chosen-bootstrap.css" />
<link rel="stylesheet" href="/plugins/Leaflet.markercluster-1.4.1/MarkerCluster.css" />
<link rel="stylesheet" href="/plugins/Leaflet.markercluster-1.4.1/MarkerCluster.Default.css" />
<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables/datatables.bootstrap.min.css" />
<link rel="stylesheet" href="/plugins/datatables/responsive.dataTables.min.css" />
<link rel="stylesheet" href="/plugins/datatables/buttons/buttons.dataTables.min.css" />

@endsection

@section('template_fastload_css')
    .dt-buttons {
        float: initial !important;
        padding-top: 5px;
        text-align: center;
    }

    .dataTables_filter{
        margin-right: 12px;
    }   
@endsection

@section('content')
    
    <div class="loading" style="display: none;">Loading&#8230;</div>
    <div id="sidebar">
        <div class="sidebar-wrapper" style="overflow-y: auto;">
            <div class="panel panel-default">
                <div class="panel-heading">
                   <div class="panel-title"><b>Sistema</b> de Planificación</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <span class="control-label col-sm-4"><b style="float: right;">Area:</b></span>
                        <div class="col-sm-8">
                            {{$query->desc}}
                        </div>
                    </div>
                    <div class="form-group">
                        <span class="control-label col-sm-4"><b style="float: right;">Usuario:</b></span>
                        <div class="col-sm-8">
                            {{Auth::user()->name}}
                        </div>
                    </div>
                    <div class="form-group" style="text-align: center; margin-top: 50px;">
                        <span class="control-label col-sm-12"><b>Planificar fecha:</b></span>
                        <div class="col-sm-12">
                            <input placeholder="Día" value="<?php echo \Carbon\Carbon::now()->format('d-m-Y');?>" type="text" class="form-control" id="fecha_planificada" readonly="true" style="text-align: center;">
                        </div>
                    </div>

                    <input type="hidden" value="" id="coordenadaX">
                    <input type="hidden" value="" id="coordenadaY">
                    <input type="hidden" value="" id="calle">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_usuario" name="id_usuario" value="{{Auth::user()->id}}">
                    <input type="hidden" id="rol" name="rol" value="{{Auth::user()->roles[0]->name}}">
                    <input type="hidden" id="area" name="area" value="{{$query->id_area}}">

                    <input type="hidden" value="false" id="copiar-atributos">
                    <input type="hidden" value="false" id="referencia">
                    <input type="hidden" value="false" id="referencia-calles">

                </div>

                <div class="panel-heading"><div class="panel-title"><b>Geometrias</b> creadas</div></div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered dataTable" id="tabla-geometrias" style="width:100%;">
                        <thead style="width:100%;">
                            <tr>
                                <th>Calle/Zona</th>
                                <th class="none">Area</th>
                                <th class="none">Descripcion</th>
                                <th class="none">Tipo de trabajo</th>
                                <th class="none">Horario</th>
                                <th class="none">Tipo de figura</th>
                                <th class="none">Datos complementarios</th>
                                <th class="none">Corte de calzada</th>
                                <th class="none">Fecha</th>
                                <th class="none">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal modal-left fade right bs-example-modal-sm" id="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow-y: auto;">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog modal-sm vertical-align-center" role="document">
                <div class="modal-content modal-content-left">
                    <form action="#" class="form-horizontal" id="nueva_geom">   

                        <div class="modal-header" style="background: #4682B4; color: #FFFFFF;">
                            <h4 class="modal-title" id="H4" style="float: left;"> Detalles del parte</h4>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <span id="spancallezona" class="control-label col-sm-4"><b>Calle/Zona:</b></span> 
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="callezona" required>
                                        <span class="input-group-btn">
                                            <button id="enabletype" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-4"><b>Descripción:</b></span>
                                <div class="col-sm-8">
                                    <select data-placeholder=" " id="descripcion" class="chosen-with-option form-control" multiple style="text-transform: uppercase;">
                                        @if (isset($campos_personalizados->campo_descripcion))
                                            @foreach(explode(',', $campos_personalizados->campo_descripcion) as $campo_desc)
                                                @if ($campo_desc != "")
                                                    <option value="{{$campo_desc}}">{{$campo_desc}}</option>
                                                @endif
                                            @endforeach
                                         @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-4"><b>Tipo de trabajo:</b></span>
                                <div class="col-sm-8">
                                    <select class="form-control chosen" data-placeholder="Seleccionar trabajo..." id="tipo_trabajo" required>
                                        <option value=""></option>
                                        @if (isset($tags))
                                            @foreach($tags as $tag)
                                                @if ($tag->grupo == "tipo_trabajo")
                                                    <option value="{{$tag->id_tag}}">{{$tag->desc}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-4"><b>Horario:</b></span>
                                <div class="col-sm-8">
                                    <div class="sliders_step1" style="padding-top: 10px;padding-bottom: 10px;"><div id="slider-range"></div></div><input class="time" type="hidden" value="450,750" />
                                    <div class="slider-time" style="text-align: center;">7:30 AM - 12:30 PM</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-4"><b>Corte de calzada:</b></span>
                                <div class="col-sm-8">
                                    <select class="form-control chosen" data-placeholder="Seleccionar corte..." id="corte_calzada" required>
                                        <option value=""></option>
                                        @if (isset($tags))
                                            @foreach($tags as $tag)
                                                @if ($tag->grupo == "corte_calzada")
                                                    <option value="{{$tag->id_tag}}">{{$tag->desc}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div id="datos_complementarios" class="datos">
                            @if (isset($datos_complementarios))
                                @foreach($datos_complementarios as $dato_complementario)
                                    <div class="form-group">        
                                        <span class="control-label col-sm-4"><b>{{$dato_complementario->desc_larga}}</b></span>
                                        <div class="col-sm-8">
                                            {!! $dato_complementario->html !!}
                                        </div>
                                    </div>            
                                @endforeach
                             @endif
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-4"><b>¿Establecer fecha?</b></span>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input type="checkbox" name="establecer_fecha" id="establecer_fecha" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="guardar" class="btn btn-success">Guardar</button>
                        </div>
                        <input type="hidden" class="id2">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-left fade right bs-example-modal-sm" id="modalview" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog modal-sm vertical-align-center" role="document">
                <div class="modal-content modal-content-left">
                    <form action="#" class="form-horizontal">   

                        <div class="modal-header" style="background: #378d30; color: #FFFFFF;">
                            <h4 class="modal-title" id="h4" style="float: left;"> Detalles del parte</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>

                        <div class="modal-body" id="mod2">
                            <div class="form-group">
                                <span id="spancallezona2" class="control-label col-sm-5"><b>Calle/Zona:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="callezona3" />
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-5"><b>Descripción:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="descripcion3"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-5"><b>Tipo de trabajo:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="tipo_trabajo3"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-5"><b>Horario:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="horario3"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-5"><b>Corte de calzada:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="corte_calzada3"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="control-label col-sm-5"><b>Fecha planificada:</b></span>
                                <div class="col-sm-7" style="padding-top: 8px;">
                                    <span class="fecha_planificada3"/>
                                </div>
                            </div>
                            <div class="datos_complementarios">
                                
                            </div>
                            <div class="form-group" id="ed">
                                <div class="col-sm-5">
                                    <button class="btn btn-success" id="editar">Editar</button>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" class="id3">
                    </form>
                </div>
            </div>
        </div>
    </div>

<div id="map" class="leaflet-container leaflet-fade-anim"></div>

@endsection

@section('footer_scripts')

<script type="text/javascript" src="/plugins/randomColor/randomColor.min.js"></script>
<script type="text/javascript" src="/plugins/leaflet/leaflet.js"></script>
<script type="text/javascript" src="/plugins/leaflet/leaflet.draw.js"></script>
<script type="text/javascript" src="/plugins/leaflet/locate/es_la.js"></script>
<script type="text/javascript" src="/plugins/leaflet-icon-pulse-master/L.Icon.Pulse.js"></script>
<script type="text/javascript" src="/plugins/leaflet/easy-button.js"></script>
<script type="text/javascript" src="/plugins/Leaflet.markercluster/leaflet.markercluster-src.js"></script>
<script type="text/javascript" src="/plugins/Leaflet.markercluster/leaflet.markercluster.js"></script>
<script type="text/javascript" src="/plugins/Leaflet.markercluster-1.4.1/leaflet.markercluster-src.js"></script>
<script type="text/javascript" src="/plugins/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/ol.js"></script>
<script type="text/javascript" src="/ubicaciones.js"></script>
<script type="text/javascript" src="/plugins/leaflet/proj4.js"></script>
<script type="text/javascript" src="/plugins/leaflet/proj4leaflet.js"></script>
<script type="text/javascript" src="/plugins/leaflet/tokml.js"></script>
<script type="text/javascript" src="/list.js"></script>

<!-- DataTables -->
<script src="/plugins/datatables/datatables.min.js"></script>
<script src="/plugins/datatables/datatables.bootstrap.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="/plugins/datatables/buttons/jszip.min.js"></script>
<script src="/plugins/datatables/buttons/buttons.html5.min.js"></script>
        
<script>
    $(document).ready( function () {

        //Predefinimos variables
        EPSG22185 = new L.Proj.CRS('EPSG:22185', '+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');
        EPSG900913 = new L.Proj.CRS('EPSG:900913','+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs');
        var getImportById = {};
        var arrayIds = new Array();
        var default_estilo_point = L.icon({ 
            iconUrl: '/plugins/leaflet/images/marker-icon-2x-blue.png',
            shadowUrl: '/plugins/leaflet/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }); 
        var firstProjection = "+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
        var secondProjection = "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs ";

        //Iniciar mapa
        map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, maxZoom: 18, minZoom: 12, zoomControl: false, closePopupOnClick: false});

        //Setear limites del mapa
        map.setMaxBounds([
            [-33.066801, -60.856018],
            [-32.817864, -60.493469]
        ]);
        
        //Pane
        map.createPane('referencias');
        map.createPane('importitems');
        //referencias.options.pane = 'referencias';
        map.getPane('importitems').style.zIndex = 402;
        map.getPane('referencias').style.zIndex = 401;

        //Definimos grupos
        drawnItems  = L.featureGroup();
        referencias = L.featureGroup({pane: 'referencias'});
        markersItems = L.markerClusterGroup({pane: 'importitems', maxClusterRadius: 15});
        polyItems = L.featureGroup({pane: 'importitems'});

        map.addLayer(referencias);
        map.addLayer(markersItems);
        map.addLayer(polyItems);

        //Añadimos controles
        L.control.layers({
            "OSM Proxy": L.tileLayer.wms('http://pyp-svr.pyp.rosario.gov.ar/mapproxy/service?', {
                layers: ['osm'],
                format: 'image/jpeg',
                crs: EPSG900913
            }).addTo(map),
            "Carto dark": L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}' + (L.Browser.retina ? '@2x.png' : '.png'), {
               attribution:'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; <a href="https://carto.com/attributions">CARTO</a>',
               subdomains: 'abcd',
               maxZoom: 20,
               minZoom: 0
             }),
            "INFOMAPA": L.tileLayer.wms('http://infomapa.rosario.gov.ar/wms/planobase?', {
                layers: ['manzanas_metropolitana', 'limites_metropolitana', 'limite_municipio', 'sin_manzanas', 'manzanas', 'espacios_verdes', 'canteros','av_circunvalacion', 'avenidas_y_boulevares', 'via_ferroviaria', 'hidrografia', 'puentes', 'islas_del_parana', 'bancos_de_arena', 'autopistas', 'nombres_de_calles', 'numeracion_de_calles'],
                format: 'image/jpeg',
                crs: EPSG22185,
                attribution: '&copy; Municipalidad de Rosario'
            }),
            "OSM": L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors'}),
            "GMap Satelital": L.tileLayer('http://www.google.com/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
                attribution: 'Google'
            }),
        },
        {'Puntos': markersItems, 'Poligonos y lineas': polyItems}, { position: 'topright', collapsed: true }).addTo(map);


        //BOTON MOSTRAR/OCULTAR PANEL
        L.easyButton({
            position: 'bottomleft', 
            states: 
            [
                {
                    stateName: 'left',        // name the state
                    icon:      'glyphicon glyphicon-chevron-left',               // and define its properties
                    title:     'Esconder panel',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        animateSidebar();
                        btn.state('right');    // change state on click!
                        $(".easy-button-button").tooltip('fixTitle');
                    }
                },
                {
                    stateName: 'right',
                    icon:      'glyphicon glyphicon-chevron-right',
                    title:     'Mostrar panel',      // like its title
                    onClick: function(btn, map) {
                        animateSidebar();
                        btn.state('left');      
                        $(".easy-button-button").tooltip('fixTitle');
                    }
                }
            ]
        }).addTo(map);

        //BOTON COPIAR ATRIBUTOS
        L.easyButton({
            position: 'bottomleft', 
            states: 
            [
                {
                    stateName: 'copiar-atributos',        // name the state
                    icon:      'glyphicon glyphicon-duplicate',               // and define its properties
                    title:     'Copiar ultimos atributos',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        $("#copiar-atributos").val("true");
                        btn.state('copiar-atributos-seleccionado');    // change state on click!
                        $(".easy-button-button").tooltip('fixTitle');
                    }
                }, 
                {
                    stateName: 'copiar-atributos-seleccionado',
                    icon:      'glyphicon glyphicon-remove',
                    title:     'Quitar ultimos atributos',
                    onClick: function(btn, map) {
                        $("#copiar-atributos").val("false");
                        btn.state('copiar-atributos');
                        $(".easy-button-button").tooltip('fixTitle');
                    },


                }
            ]
        }).addTo(map);

        //BOTON PUNTOS DE REFERENCIA
        L.easyButton({
            position: 'bottomleft',
            states: 
            [
                {
                    stateName: 'puntos-referencia',        // name the state
                    icon:      'glyphicon glyphicon-record',               // and define its properties
                    title:     'Puntos como referencia',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        $('#referencia').val("true");
                        btn.state('puntos-referencia-seleccionado');    // change state on click!
                        $(".easy-button-button").tooltip('fixTitle');
                    }
                },
                {
                    stateName: 'puntos-referencia-seleccionado',
                    icon:      'glyphicon glyphicon-remove',
                    title:     'Quitar puntos como referencia',      // like its title
                    onClick: function(btn, map) {
                        $('#referencia').val("false");
                        btn.state('puntos-referencia');
                        $(".easy-button-button").tooltip('fixTitle');
                }
            }]
        }).addTo(map);

        //BOTON LINEAS DE REFERENCIA
        L.easyButton({
            position: 'bottomleft',
            states: 
            [{
                    stateName: 'lineas-referencia',        // name the state
                    icon:      'glyphicon glyphicon-road',               // and define its properties
                    title:     'Calles como referencia',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        $('#referencia-calles').val("true");
                        btn.state('lineas-referencia-seleccionado');    // change state on click!
                        $(".easy-button-button").tooltip('fixTitle');
                    }
                }, {
                    stateName: 'lineas-referencia-seleccionado',
                    icon:      'glyphicon glyphicon-remove',
                    title:     'Quitar calles como referencia',      // like its title
                    onClick: function(btn, map) {
                        $('#referencia-calles').val("false");
                        btn.state('lineas-referencia');    
                        $(".easy-button-button").tooltip('fixTitle');
                    }
            }]
        }).addTo(map);

        drawbutton = L.easyButton({
            position: 'topleft',
            states: 
            [{
                    stateName: 'draw-on',        // name the state
                    icon:      'glyphicon glyphicon-pencil',               // and define its properties
                    title:     'Habilitar edición',      // like its title
                    onClick: function(btn, map) {       // and its callback
                        markersItems.clearLayers();
                        polyItems.clearLayers();
                        drawnItems.addTo(map);
                        habilitarDraw();
                        btn.state('draw-off');
                    }
                }, {
                    stateName: 'draw-off',
                    icon:      'glyphicon glyphicon-remove',
                    title:     'Deshabilitar edición',      // like its title
                    onClick: function(btn, map) {
                        map.removeControl(map.drawControl);
                        cargarJSON(0);
                        btn.state('draw-on');    
                    }
            }]
        }).addTo(map);
        drawbutton.disable();

        //BOTON DIFERENCIAR TRABAJOS
        boton_diferenciar_trabajos = L.easyButton({
            position: 'bottomleft',
            states: 
            [{
                stateName: 'diferenciar-x-tipo',
                icon:      'glyphicon glyphicon-fullscreen',
                title:     'Diferenciar puntos por trabajo',
                onClick: function(btn, map) {
                    referencias.clearLayers();
                    markersItems.eachLayer(function (layer) {
                        colorforvar = colorBase(layer);
                        var pulsingIcon = L.icon.pulse({iconSize:[10,10],color: colorforvar});
                        layer.setIcon(pulsingIcon);
                    });
                    polyItems.eachLayer(function (layers) {
                        layers.eachLayer(function (layer) {
                            colorforvar = colorBase(layer);
                            L.geoJson(layer.toGeoJSON(), {pane: 'referencias'}).setStyle({
                                    weight: "12.00",
                                    opacity: "0.80",
                                    color: colorforvar,
                                    fillOpacity: "0"
                                }
                            ).addTo(referencias);
                        });
                    });
                    map.addControl(new legendControl());
                    btn.state('diferenciar-x-tipo-remove');
                }
            },  
            {
                stateName: 'diferenciar-x-tipo-remove',
                icon:      'glyphicon glyphicon-remove',
                title:     'Quitar referencias',      // like its title
                onClick: function(btn, map) {
                    map.removeControl(map.legendControl);
                    cargarJSON(0);
                    btn.state('diferenciar-x-tipo');
                }
            }]
        }).addTo(map);

        function colorBase(layer) {
            var colorforvar = "";
            for (var i in ramdomColorByTipo)
            {
                if(layer.feature.properties.tipo_trabajo == i)
                {
                    colorforvar = ramdomColorByTipo[i];
                    return colorforvar;
                }
            }
        }

        //MODIFICAR CONTROL ZOOM
        L.control.zoom({
             position:'bottomright'
        }).addTo(map);

        //ASIGNAR REFERENCIAS
        var legendControl = L.Control.extend({
            options: {
                position: 'bottomright'
            },
            onAdd: function (map) {
                map.legendControl = this;
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control legend');
                container.style.backgroundColor = 'white';
                container.style.padding = '5px';
                container.innerHTML += "<h5 style='text-align: center;font-weight: bold;'><span>REFERENCIAS</span></h5>";
                    for (var i in ramdomColorByTipo)
                    {  
                        container.innerHTML += "<div><i style='background:"+ramdomColorByTipo[i] +";width: 18px;height: 18px;float: left;margin-right: 8px;'></i><span> "+i+"</span></div><br>";
                    }

                return container;
            }
        });
        
        function habilitarDraw(){
            if($("#rol").val() == "administracion"){
                map.addControl(map.drawControl = new L.Control.Draw({
                    edit: {
                        featureGroup: drawnItems,
                        poly : {
                            allowIntersection : false
                        },
                        icon: new L.DivIcon({
                            iconSize: new L.Point(13, 13),
                        })
                    },
                    draw: {
                        polygon : false,
                        polyline : false,
                        rectangle : false,
                        circle: false,
                        marker: false,
                    }
                }));
            }
            else{
                map.addControl(map.drawControl = new L.Control.Draw({
                    edit: {
                        featureGroup: drawnItems,
                        poly : {
                            allowIntersection : false
                        },
                        icon: new L.DivIcon({
                            iconSize: new L.Point(13, 13),
                        }),
                    },
                    draw: {
                        polygon : {
                            allowIntersection: false,
                            showArea:true,
                            shapeOptions: {
                                color: '#2D84CB'
                            },
                            icon: new L.DivIcon({
                                iconSize: new L.Point(13, 13),
                            })
                        },
                        polyline : {
                            shapeOptions: {
                                color: '#2D84CB',
                                weight: 12
                            },
                            icon: new L.DivIcon({
                                iconSize: new L.Point(13, 13),
                            }),
                            metric: true,
                            feet: false
                        },
                        rectangle : false,
                        circle: false,
                        marker: true,
                    }
                }));
            }

            L.Edit.Poly = L.Edit.Poly.extend({
                options : {
                    icon: new L.DivIcon({
                         iconSize: new L.Point(13, 13),
                         className: 'leaflet-div-icon leaflet-editing-icon my-own-icon'
                    })
                }
            });
        }

        $("#fecha_planificada").datepicker({
            numberOfMonths: 1,   
            showAnim: "slideDown",
            dateFormat: "dd-mm-yy",
            minDate: "-5D",
            maxDate: "+5D",
            onSelect: function(dateText) {
                cargarJSON(0);
            }
        });

        //Añadir las geometrias dibujadas
        map.on('draw:created', function(event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);
            item = layer.toGeoJSON();
            //Limpiar inputs
            resetAllValues();
            initSlider();
            if(item.geometry.type == "Point")
            {
                agregarpuntos(item.geometry.coordinates[0], item.geometry.coordinates[1], "drawpoint");
            }
            else if(item.geometry.type == "Polygon" || item.geometry.type == "LineString"){
                map.fitBounds(layer.getBounds());
                $('#callezona').attr('readonly', false);
                $('#spancallezona').html('<b>Zona/Recorrido</b>')
                $('#modal').on('shown.bs.modal', function () {
                    $('#callezona').focus();
                });
                properties_geom(item, tipo_guardado = "guardar");
            }
            $("#modal").modal({keyboard: false, backdrop: 'static'});
        });

        function properties_geom(item, tipo_guardado ) {
            $("#guardar").one("click", function(event) {
                event.preventDefault();
                id_info         = $('.id2').val();
                if(item != "null"){
                    geometrias      = item.geometry.coordinates;
                    tipo_geometria  = item.geometry.type;
                }
                callezona       = $('#callezona').val();
                descripcion     = $('#descripcion').val();
                area            = $('#area').val();
                tipo_trabajo    = $('#tipo_trabajo').val();
                horario         = $('.slider-time').html();
                corte_calzada   = $('#corte_calzada').val();
                token           = $('input[name=_token]').val();
                id_usuario      = $('#id_usuario').val();
                fecha_planificada   = $('#fecha_planificada').val();
                if($('#establecer_fecha').val() == "on"){
                    fecha_planificada   = $('#fecha_planificada').val();
                }
                else{
                    fecha_planificada   = null;
                }
                datos_complementarios = $("#datos_complementarios :input").serialize();

                if(descripcion == "" || callezona == "" || tipo_trabajo == "" || horario == "" || corte_calzada == "" || tipo_trabajo == "Elija uno" || corte_calzada == "Elija uno" || fecha_planificada == ""){
                    properties_geom(item, tipo_guardado);
                    alert("Todos los campos son requeridos");
                }
                else{
                    $.ajaxSetup({
                        header:$('meta[name="_token"]').attr('content')
                    })
                    if(tipo_guardado == "guardar"){
                        $.ajax({
                            type:"POST",
                            url:'{{ route("planificacion_guardar") }}',
                            data: { 'geometrias': geometrias, 'tipo_geometria': tipo_geometria, 'callezona': callezona, 'descripcion': descripcion, 'id_area': area, 'id_tipo_trabajo': tipo_trabajo, 'fecha_planificada': fecha_planificada , 'horario': horario, 'id_corte_calzada': corte_calzada, '_token': token, 'id_usuario': id_usuario, 'datos_complementarios' : datos_complementarios },
                            dataType: 'json',
                            success: function(data) {
                                cargarJSON(0);
                                $("#txtDireccionesLugares").focus();
                            }
                        });
                    }
                    else if (tipo_guardado == "update"){
                        $.ajax({
                            type:"POST",
                            url:'{{ route("planificacion_update", ["id"]) }}'.replace('id', id_info),
                            data: {'callezona': callezona, 'descripcion': descripcion, 'id_area': area, 'id_tipo_trabajo': tipo_trabajo, 'horario': horario, 'id_corte_calzada': corte_calzada, '_token': token, 'fecha_planificada': fecha_planificada , 'id_usuario': id_usuario, 'datos_complementarios' : datos_complementarios },
                            dataType: 'json',
                            success: function(data) {
                                cargarJSON(0);
                                $("#txtDireccionesLugares").focus();
                            }
                        });
                    }
                    $("#modal").modal('hide'); 
                }    
            });
        }

        function resetAllValues() {
            if($("#copiar-atributos").val() == "true"){
                $('#callezona').val("");
            }
            else{
                $('#callezona').attr('readonly', false);
                $('#callezona').val("");
                $('#descripcion').val("").trigger('chosen:updated');
                $('#tipo_trabajo').val("").trigger('chosen:updated');
                $('#corte_calzada').val("").trigger('chosen:updated');
                $('#datos_complementarios').find(".chosen").val("").trigger('chosen:updated');
                $('#datos_complementarios').find(".chosen-with-option").val("").trigger('chosen:updated');
                $('#datos_complementarios').find('input').val("");
                $('.slider-time').html('7:30 AM - 12:30 PM');
                $("#establecer_fecha").val( "on" );
                $("#establecer_fecha").prop('checked',true);
            }
        }

        map.on('zoomend', function() {
            var currentZoom = map.getZoom();
            if(currentZoom >= 15) {
                drawbutton.enable();
            }
            else {
                drawbutton.disable();
            }
        });

        //Updatear geometrias modificadas en la BD
        map.on('draw:edited', function (e) {
            $(".loading").attr("style", "display: block;");
            setTimeout(function(){
                var layers = e.layers;
                geometrias = new Array();
                tipo_geometria = new Array();
                id_info = new Array();
                direccion_new = new Array();
                layers.eachLayer(function (layer) {
                    togeojson = layer.toGeoJSON();
                    if(togeojson.geometry.type == "Point"){
                        conversionto22185 = proj4(secondProjection,firstProjection,togeojson.geometry.coordinates);
                        x = conversionto22185[0];
                        y = conversionto22185[1];
                        $.ajaxSetup({
                            async: false,
                            "error": function(){
                                direccion_new.push("SIN CALLE");
                            }
                        });
                        $.getJSON("https://ws.rosario.gob.ar/ubicaciones/public/direccion/punto/"+x+"/"+y+"/" ,function(data){
                            if(data.bis == true){ 
                                bis = "BIS";
                            }
                            else{
                                bis = ""
                            }
                            if(data.letra){
                                letra = data.letra;
                            }
                            else{
                                letra = "";
                            }
                            direccion_nueva = data.calle.nombre.trim()+" "+data.altura+" "+bis+letra;
                            direccion_new.push(direccion_nueva);
                        });
                    }
                    else{
                        direccion_new.push("null");
                    }
                    geometrias.push(togeojson.geometry.coordinates);
                    tipo_geometria.push(togeojson.geometry.type);
                    id_info.push(togeojson.properties.id_info);              
                });
                if(id_info.length > 0){
                    token = $('input[name=_token]').val();
                    $.ajaxSetup({
                        header:$('meta[name="_token"]').attr('content')
                    })
                    $.ajax({
                        type:"POST",
                        url:'{{ route("planificacion_update_geom") }}',
                        data: {'geometrias': geometrias, 'id_info': id_info, 'tipo_geometria': tipo_geometria, 'direccion_new': direccion_new, '_token': token},
                        dataType: 'json',
                        success: function(data) {
                            cargarJSON(0);
                        }
                    });
                }
                $(".loading").attr("style", "display: none;");
            }, 200);
            
        });

        //Dar de bajas geometrias en la BD
        map.on('draw:deleted', function (e) {
            var layers = e.layers;
            id_usuario = new Array();
            id_info = new Array();
            layers.eachLayer(function (layer) {
                togeojson = layer.toGeoJSON();
                id_info.push(togeojson.properties.id_info);
                id_usuario.push(togeojson.properties.id_usuario);
            });
            if(id_info.length > 0){
                token = $('input[name=_token]').val();
                $.ajaxSetup({
                    header:$('meta[name="_token"]').attr('content')
                })
                $.ajax({
                    type:"POST",
                    url:'{{ route("planificacion_baja") }}',
                    data: {'_token': token, 'id_usuario': id_usuario, 'id_info': id_info },
                    dataType: 'json',
                    success: function(data) {
                        cargarJSON(0);
                    }
                });
            }
        });

        var ramdomColorByTipo = new Array();

        function onEachFeature(feature, layer, id) {
            if (feature.properties.id_info) {
                arrayIds.push(feature.properties.id_info);
                getImportById[feature.properties.id_info] = layer;
            }

            if(ramdomColorByTipo.length > 0){
                $.each(ramdomColorByTipo ,function(key, value){
                    if(key != feature.properties.tipo_trabajo)
                    {   
                        ramdomColorByTipo[feature.properties.tipo_trabajo] = randomColor();
                    }   
    
                });
            }
            else{
                ramdomColorByTipo[feature.properties.tipo_trabajo] = randomColor();
            }

            drawnItems.addLayer(layer);   
        }

        //EVENTO CLICK ESTABLECER FECHA
        $(document).on('change','#establecer_fecha',function(cb){
            if($(this).is(':checked')){
                $('#establecer_fecha').val("on");
            } else{
                $('#establecer_fecha').val("off");
            }    
        });

        //Deshabilitar menu contextual sobre el mapa
        $("#map").contextmenu(function(e) {
            e.preventDefault();
        });

        markersItems.on('click', function(event) {
            viewClick(0, event.layer);
        });

        polyItems.on('click', function(event) {
            viewClick(0, event.layer);
        });

        function viewClick(id, layer) {
            if(id != 0){
                layer = getImportById[id];
                props = layer.feature.properties;
            }
            else if(layer != 0){
                props = layer.feature.properties;
            }
            $('.callezona3').html(props.callezona);
            $('.descripcion3').html(props.descripcion);
            $('.tipo_trabajo3').html(props.tipo_trabajo);
            $('.corte_calzada3').html(props.corte_calzada);
            $('.fecha_planificada3').html(props.fecha_planificada);
            $('.horario3').html(props.horario);
            $('.id3').val(props.id_info);
            $('.datos_complementarios').html("");

            datos_json = JsonORparse(props.datos_complementarios);

            for(var p in datos_json){
                for(var i = 0;i < globalData.length; i++)
                {
                    if(globalData[i].desc_corta == p)
                    {
                        $(".datos_complementarios").append('<div class="form-group"><span class="control-label col-sm-5"><b>'+globalData[i].desc_larga +':</b></span><div class="col-sm-7" style="padding-top: 8px;"><span class="dato_complementario"/>'+datos_json[p]+'</span></div></div>');
                        break;
                    }   
                }
            }
            
            $("#modalview").modal();

            if(layer.editing._marker)
            {
                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
                var marker = L.marker(layer.getLatLng(),{icon: pulsingIcon}).addTo(referencias);
                $('#callezona').attr('readonly', true);
                $('#spancallezona2').html('<b>Calle</b>');
            }
            else if(layer.editing._poly){
                map.fitBounds(layer.getBounds());
                $('#callezona').attr('readonly', false);
                $('#spancallezona2').html('<b>Zona/Recorrido</b>');
            }

            $(document).on('click', '.modal-backdrop', function() {
                $("#modalview").modal('hide');
            });

            $('#modalview').on('hide.bs.modal', function () {
                if(layer.editing._marker && marker){
                    map.removeLayer(marker);
                }
            });   
        }

        function JsonORparse(json){
            if (json && typeof json === "object") {
                return json;
            } else {
                return JSON.parse(json);
            }
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
                    if (resultado.properties.subtipo == "CALLE" && $("#referencia-calles").val() == "true" ){
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
                        $('#calle').val(
                            resultado.properties
                            ? resultado.properties.name
                            : ''
                        );
                        agregarpuntos(resultado.geometry.coordinates[0], resultado.geometry.coordinates[1], "buscador");
                        $("#txtDireccionesLugares").val("");
                    }
                },
                url: 'https://ws.rosario.gob.ar/ubicaciones/public/geojson/ubicaciones',
                mostrarReferenciasAlturas: true,
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
                    if(geojson.result.limit == 100 && !(geojson.result.total <= offset)){
                        offset = offset+100 ;
                        getJsonCalles(offset, calles);
                    }
                    geojson_poligonos.push(geojson.result);
                }
            });
        }
        var globalData;
        //Cargar Geojson con las geometria del dia de la fecha
        cargarJSON(1);
        function cargarJSON(estado){
            $.getJSON('{{ route("get_datoscomplementarios") }}', function(data){
                globalData = data;
            });
            
            if(estado == 0){
                markersItems.clearLayers();
                polyItems.clearLayers();
                drawnItems.clearLayers();
                referencias.clearLayers();
                map.removeLayer(drawnItems);
                $("#feature-list tr").remove();
                boton_diferenciar_trabajos.state('diferenciar-x-tipo');
                drawbutton.state('draw-on');
                if(map.legendControl != undefined){
                    map.removeControl(map.legendControl);
                }
                if(map.drawControl != undefined){
                    map.removeControl(map.drawControl);
                }
            }

            $.getJSON('{{ route("get_estilo_capa") }}', function (estilos) {
                //var arrayIds = new Array();
                arrayIds.length = 0;
                var data = {
                    "area": $('#area').val(),
                    "fecha_planificada": $('#fecha_planificada').val(),
                    "rol": $('#rol').val(),
                    "_token": $('input[name=_token]').val()
                };
                $.ajax({
                    type:"POST",
                    url:'{{ route("get_puntos") }}',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, {
                                pane: 'importitems',
                                onEachFeature: onEachFeature, 
                                pointToLayer: function(feature, latlng) {
                                    if(feature.properties.fecha_planificada == null){
                                        estilo_punto = L.icon({
                                            iconUrl: "/plugins/leaflet/images/marker-icon-2x-grey.png",
                                            iconSize: [25, 41],
                                            iconAnchor: [12, 41],
                                            popupAnchor: [1, -34],
                                            shadowSize: [41, 41]
                                        });
                                        return L.marker(latlng, {icon: estilo_punto});
                                    }
                                    else{
                                        estilo_punto = L.icon({
                                            iconUrl: estilos[feature.properties.id_area].iconUrl,
                                            iconSize: [25, 41],
                                            iconAnchor: [12, 41],
                                            popupAnchor: [1, -34],
                                            shadowSize: [41, 41]
                                        });
                                        return L.marker(latlng, {icon: estilo_punto});
                                    }
                                }
                            }).addTo(markersItems);
                            lista();
                        }
                        else {
                            console.log("No se encontraron puntos");
                        }
                        $(".leaflet-draw-toolbar a").tooltip('fixTitle');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                $.ajax({
                    type:"POST",
                    url:'{{ route("get_poligonos") }}',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, {
                                pane: 'importitems',
                                onEachFeature: onEachFeature, 
                                style: function(feature) {
                                    if(feature.properties.fecha_planificada == null){
                                        return {
                                            weight: "8.00",
                                            opacity: "1.00",
                                            color: "#5D5D5D",
                                            dashArray: "15, 10, 5, 10",
                                            fillOpacity: "0.40",
                                            fillColor: "#494949"
                                        };
                                    } else {
                                        return {
                                            weight: estilos[feature.properties.id_area].weight,
                                            opacity: estilos[feature.properties.id_area].opacity,
                                            color: estilos[feature.properties.id_area].color,
                                            dashArray: estilos[feature.properties.id_area].dashArray,
                                            fillOpacity: estilos[feature.properties.id_area].fillOpacity,
                                            fillColor: estilos[feature.properties.id_area].fillColor
                                        };
                                    }                                 
                                }, 
                            }).addTo(polyItems);
                            lista();
                        }
                        else {
                            console.log("No se encontraron poligonos");
                        }
                        $(".leaflet-draw-toolbar a").tooltip('fixTitle');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                $.ajax({
                    type:"POST",
                    url:'{{ route("get_lineas") }}',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        geojson = jQuery.parseJSON(response[0].row_to_json);
                        if(geojson.features != null){
                            L.geoJSON(geojson, { 
                                pane: 'importitems',
                                onEachFeature: onEachFeature, 
                                style: function(feature) {
                                    if(feature.properties.fecha_planificada == null){
                                        return {
                                            weight: "8.00",
                                            opacity: "1.00",
                                            color: "#5D5D5D",
                                            dashArray: "15, 10, 5, 10",
                                            fillOpacity: "0.40",
                                            fillColor: "#494949"
                                        };
                                    } else {
                                        return {
                                            weight: estilos[feature.properties.id_area].weight,
                                            opacity: estilos[feature.properties.id_area].opacity,
                                            color: estilos[feature.properties.id_area].color,
                                            dashArray: estilos[feature.properties.id_area].dashArray,
                                            fillOpacity: estilos[feature.properties.id_area].fillOpacity,
                                            fillColor: estilos[feature.properties.id_area].fillColor
                                        };
                                    }      
                                }, 
                            }).addTo(polyItems);
                            lista();
                        }
                        else {
                            console.log("No se encontraron polylineas");
                        }
                        $(".leaflet-draw-toolbar a").tooltip('fixTitle');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                console.log(arrayIds);
                initDatatable(arrayIds);
            });
        }

        //Agregar puntos desde el buscador y convertirlos
        function agregarpuntos(coordenadaX, coordenadaY, origen){
            if(origen == "drawpoint"){
                conversionto22185 = proj4(secondProjection,firstProjection,item.geometry.coordinates);
                x = conversionto22185[0];
                y = conversionto22185[1];
                $.ajaxSetup({
                    "error": function(){
                        $('#callezona').attr('readonly', false);
                    }
                });
                $.getJSON("https://ws.rosario.gob.ar/ubicaciones/public/direccion/punto/"+x+"/"+y+"/" ,function(data){
                    if(data.bis == true){ 
                        bis = "BIS";
                    }
                    else{
                        bis = ""
                    }
                    if(data.letra){
                        letra = data.letra;
                    }
                    else{
                        letra = "";
                    }
                    $('#callezona').val(data.calle.nombre.trim()+" "+data.altura+" "+bis+letra);
                });
                conversion = item.geometry.coordinates;
            }
            else if (origen == "buscador"){
                conversion = proj4(firstProjection,secondProjection,[coordenadaX,coordenadaY]);
            } 

            if($('#referencia').val() == "true"){
                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
                var marker = L.marker([conversion[1], conversion[0]],{icon: pulsingIcon}).addTo(referencias);
                map.setView([conversion[1], conversion[0]], 16);
                $("#txtDireccionesLugares").focus();
            }
            else{
                point = L.marker([conversion[1], conversion[0]]).addTo(drawnItems);
                drawnItems.addLayer(point);
                item = point.toGeoJSON();

                resetAllValues();
                initSlider();
               
                map.setView([item.geometry.coordinates[1],item.geometry.coordinates[0]], 16);
                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
                var marker = L.marker([item.geometry.coordinates[1],item.geometry.coordinates[0]],{icon: pulsingIcon}).addTo(referencias);
                calle13 = $('#calle').val();
                $('#callezona').val(calle13);
                $('#callezona').attr('readonly', true);
                $('#spancallezona').html('<b>Calle</b>')
                $('#modal').on('shown.bs.modal', function () {
                    $('#descripcion').focus();
                });

                $("#modal").modal({keyboard: false, backdrop: 'static'});

                properties_geom(item, tipo_guardado = "guardar");
            }
        }

        //Sidebar corrediza
        function animateSidebar() {
          $("#sidebar").animate({
            width: "toggle"
          }, 350, function() {
            map.invalidateSize();
          });
        }

        //Iniciar slider dentro del modal
        function initSlider(time1, time2){
            if(!time1 || !time2){
                time1 = 450;
                time2 = 750;
            }
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 1440,
                step: 15,
                values: [time1, time2],
                slide: function (e, ui) {
                    var hours1 = Math.floor(ui.values[0] / 60);
                    var minutes1 = ui.values[0] - (hours1 * 60);
                    if (hours1.length == 1) hours1 = '0' + hours1;
                    if (minutes1.length == 1) minutes1 = '0' + minutes1;
                    if (minutes1 == 0) minutes1 = '00';
                    if (hours1 >= 12) {
                        if (hours1 == 12) {
                            hours1 = hours1;
                            minutes1 = minutes1 + " PM";
                        } else {
                            hours1 = hours1 - 12;
                            minutes1 = minutes1 + " PM";
                        }
                    } else {
                        hours1 = hours1;
                        minutes1 = minutes1 + " AM";
                    }
                    if (hours1 == 0) {
                        hours1 = 12;
                        minutes1 = minutes1;
                    }
                    var hours2 = Math.floor(ui.values[1] / 60);
                    var minutes2 = ui.values[1] - (hours2 * 60);
                    if (hours2.length == 1) hours2 = '0' + hours2;
                    if (minutes2.length == 1) minutes2 = '0' + minutes2;
                    if (minutes2 == 0) minutes2 = '00';
                    if (hours2 >= 12) {
                        if (hours2 == 12) {
                            hours2 = hours2;
                            minutes2 = minutes2 + " PM";
                        } else if (hours2 == 24) {
                            hours2 = 11;
                            minutes2 = "59 PM";
                        } else {
                            hours2 = hours2 - 12;
                            minutes2 = minutes2 + " PM";
                        }
                    } else {
                        hours2 = hours2;
                        minutes2 = minutes2 + " AM";
                    }
                    $('.slider-time').html(hours1 + ':' + minutes1+' - '+hours2 + ':' + minutes2);

                    $('.time').val(ui.values[0]+','+ui.values[1]);
                }
            });
        }

        //Iniciar/Reiniciar lista by list.js
        function lista(){
            var options = {
                valueNames: ['feature-name', 'feature-area', 'feature-trabajo']
            };
            var featureList = new List('users', options);
        }

        //Puntos de referencia
        $('.btn-group').click(function () {
            if($('#referencia').val() == "false"){
                $('#referencia').val("true");
            }
            else{
                $('#referencia').val("false");
            }
        });

        //Editar datos de una geometria en la BD
        $("#editar").click(function (e){
            e.preventDefault();
            $("#modalview").modal('hide');
            $("#modal").modal();
            resetAllValues()

            layer = getImportById[$(".id3").val()];

            //Limpiar inputs
            var array = layer.feature.properties.descripcion.split(",");
            array2 = $("#descripcion>option").map(function() { return $(this).val(); });

            diferencia = $(array).not(array2).get();
            $.each( diferencia, function( key, value ) {
                $('#descripcion').append($('<option>', {
                    value: value,
                    text: value
                }));
            });
            
            $('#descripcion').val(array).trigger('chosen:updated');
            $('#tipo_trabajo').val(layer.feature.properties.id_tipo_trabajo).trigger('chosen:updated');
            $('#corte_calzada').val(layer.feature.properties.id_corte_calzada).trigger('chosen:updated');
            $('.slider-time').html(layer.feature.properties.horario);

            if(layer.feature.properties.fecha_planificada == null){
                $('#establecer_fecha').prop('checked',false);
                $("#establecer_fecha").val( "off" );
            }
            else{
                $('#establecer_fecha').prop('checked',true);
                $("#establecer_fecha").val( "on" );
            }

            //Datos complementarios
            $(".datos select").each(function (){
                selects = this;
                datos_json = JsonORparse(layer.feature.properties.datos_complementarios);
                $.each(datos_json, function(item, value){
                    if($(selects).attr("name") == item){
                        $(selects).val(value);
                    }
                });
            });
            $(".datos input").each(function (){
                inputs = this;
                datos_json = JsonORparse(layer.feature.properties.datos_complementarios);
                $.each(datos_json, function(item, value){
                    if($(inputs).attr("name") == item){
                        $(inputs).val(value);
                    }
                });
            });
            $('.datos').find(".chosen-with-option").trigger('chosen:updated');
            $('.datos').find(".chosen").trigger('chosen:updated');

            id = $(".id3").val();
            $('.id2').val(id);

            if(layer.feature.geometry.type == "Point")
            {
                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
                var marker = L.marker(layer.getLatLng(),{icon: pulsingIcon}).addTo(referencias);

                $('#callezona').val(layer.feature.properties.callezona);
                $('#callezona').attr('readonly', true);
                $('#spancallezona').html('<b>Calle</b>')
                $('#modal').on('shown.bs.modal', function () {
                    $('#descripcion').focus();
                });
            }
            else if(layer.feature.geometry.type == "Polygon" || layer.feature.geometry.type == "LineString"){

                $('#callezona').val(layer.feature.properties.callezona);
                $('#callezona').attr('readonly', false);
                $('#spancallezona').html('<b>Zona/Recorrido</b>')
                $('#modal').on('shown.bs.modal', function () {
                    $('#callezona').focus();
                });
            }
            var hms = layer.feature.properties.horario;   // your input string
            var a = hms.split(' - '); // split it at the colons
            var horario1 = new Date("1/1/2013 " + a[0]);
            var horario2 = new Date("1/1/2013 " + a[1]); 
            minutos1 = horario1.getHours()*60 + horario1.getMinutes();
            minutos2 = horario2.getHours()*60 + horario2.getMinutes();
            initSlider(minutos1,minutos2);

            properties_geom(item = "null", tipo_guardado = "update");
        });

        $(".chosen-with-option").chosen({
            create_option: true,
            persistent_create_option: true,
            skip_no_results: true
        });

        $(".chosen").chosen();

        // Tooltip para opciones del mapa
        $('.leaflet-control a').attr({
            'data-toggle': "tooltip", 
            'data-placement':"right"
        });
        $('.easy-button-button').attr({
            'data-toggle': "tooltip", 
            'data-placement':"right"
        });
        $('.leaflet-control-zoom a').attr({
            'data-toggle': "tooltip", 
            'data-placement':"left"
        });
        $('[data-toggle="tooltip"]').tooltip();

        function searchData(){
            return arrayIds;
        }

        function initDatatable(){
            if (!$.fn.dataTable.isDataTable('#tabla-geometrias')) {
                $('#tabla-geometrias').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "scrollX": true,
                    "responsive": true,
                    "paging": false,
                    "info": false,
                    ajax: {
                        url: '{{ route("datatables_geometrias-planificacion") }}',
                        data: function (d) {
                            d.ids = searchData();
                        },
                    },
                    "columns":[
                        {data: 'callezona', name: 'planificacion_info.callezona', responsivePriority: 1},
                        {data: 'area', name: 'area.desc'},
                        {data: 'descripcion', name: 'planificacion_info.descripcion'},
                        {data: 'tipo_trabajo', name: 'tipo_trabajo.desc'},
                        {data: 'horario', name: 'planificacion_info.horario'},
                        {data: 'corte_calzada', name: 'corte_calzada.desc'},
                        {data: 'tipo_geometria', name: 'planificacion_info.tipo_geometria'},
                        {data: 'datos_complementarios', name: 'planificacion_info.datos_complementarios'},
                        {data: 'fecha_planificada', name: 'planificacion_info.fecha_planificada'},
                        {data: 'action', name: 'action' , orderable: false, searchable: false}
                    ],
                    "language":{
                        url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Exportar a excel'
                        },
                    ],
                });
            } else {
                $('#tabla-geometrias').DataTable().ajax.reload();
            }
        }

        $(document).on('click', '.ubicar', function (e) {
            id = $(this).data('id');
            viewClick(id, 0);     
        });
        $('#enabletype').on('click', function(e){
            e.preventDefault();
            $('#callezona').attr('readonly', false);
        });

    });
</script>

@endsection

