@extends('layouts.app')

@section('sidebar')
    

    <div id="sidebar">
        <div class="sidebar-wrapper">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Bienvenidos al Sistema de Planificacion Diaria
                </div>
                <div class="panel-body">
                    <p><b>Area:</b> {{Auth::user()->area}} </p>
                    <p><b>Fecha:</b> {{ \Carbon\Carbon::now()->format('d-m-Y') }} </p>
                    <p><b>Usuario:</b> {{Auth::user()->name}} </p>

                    <a href='#' id="export" class="btn btn-success">Guardar planificación</a>

                    <input type="hidden" value="" id="coordenadaX">
                    <input type="hidden" value="" id="coordenadaY">
                    <input type="hidden" value="" id="calle">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_usuario" name="id_usuario" value="{{Auth::user()->id}}">
                    <input type="hidden" id="area" name="area" value="{{Auth::user()->area}}">
                    <input type="hidden" id="fecha" name="fecha" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                </div>
            </div>
            <div id="users">
                <div class="panel panel-default" id="features" style="margin-top: -20px;">
                    <div class="panel-heading">Geometrias creadas</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-xs-8 col-md-8">
                          <input type="text" class="form-control search" placeholder="Filter">
                        </div>
                        <div class="col-xs-4 col-md-4">
                          <button type="button" class="btn btn-primary pull-right sort asc" data-sort="feature-name" id="sort-btn"><i class="glyphicon glyphicon-sort-by-alphabet"></i></button>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="sidebar-table" style="margin-top: -17px;">
                    <table class="table table-hover" id="feature-list">
                        <thead class="hidden">
                            <tr>
                                <th>Icon</th>
                            </tr><tr>
                            </tr><tr>
                                <th>Name</th>
                            </tr><tr>
                            </tr><tr>
                                <th>Chevron</th>
                            </tr><tr>
                            </tr>
                        </thead>
                        <tbody class="list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de error-->

    @if($errors->has())
        <div class="alert alert-warning" role="alert" id="ocultar">
           @foreach ($errors->all() as $error)
              <div>{{ $error }}</div>
          @endforeach
        </div>
    @endif 


    <!-- Mensajes de exito-->

     @if (session('status'))
        <div class="alert alert-success" id="ocultar">
            {{ session('status') }}
        </div>
    @endif

@endsection

@section('content')

<div id="map" class="leaflet-container leaflet-fade-anim"></div>


        
<script>
    $(document).ready( function () {

        //Predefinimos variables
        osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors'});
        EPSG22185 = new L.Proj.CRS('EPSG:22185', '+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');
        var getImportById = {};
        var default_estilo_point = L.icon({ 
            iconUrl: '/plugins/leaflet/images/marker-icon-2x-blue.png',
            shadowUrl: '/plugins/leaflet/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        }); 

        //Iniciar mapa
        map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, minZoom: 12, closePopupOnClick: false});

        //Setear limites del mapa
        map.setMaxBounds([
            [-33.066801, -60.856018],
            [-32.817864, -60.493469]
        ]);
        
        //Definimos grupos
        drawnItems  = L.featureGroup().addTo(map);

        //Añadimos controles
        L.control.layers({
        'OpenStreetMap':osm.addTo(map),
        "Google Map Satelital": L.tileLayer('http://www.google.com/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
            attribution: 'google'
        }),
        "INFOMAPA": L.tileLayer.wms('http://infomapa.rosario.gov.ar/wms/planobase?', {
            layers: ['distritos_descentralizados','rural_metropolitana','manzanas_metropolitana','limites_metropolitana','limite_municipio','sin_manzanas','manzanas','parcelas','manzanas_no_regularizada','espacios_verdes','canteros','av_circunvalacion','avenidas_y_boulevares','sentidos_de_calle','via_ferroviaria','hidrografia','puentes','islas_del_parana','bancos_de_arena','autopistas','nombres_de_calles','numeracion_de_calles'],
            format: 'image/jpeg',
            crs: EPSG22185,
            attribution: '&copy; Municipalidad de Rosario'
        }),
        "Toner LITE": L.tileLayer('http://tile.stamen.com/toner-lite/{z}/{x}/{y}.png', {
            attribution: 'Toner'
        }),
        "Carto LITE": L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attributions">CARTO</a>'
        })}, {'Capa':drawnItems}, { position: 'topright', collapsed: false }).addTo(map);

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
                    agregarpuntos(resultado.geometry.coordinates[0], resultado.geometry.coordinates[1]);
                },
                url: 'http://ws.rosario.gov.ar/ubicaciones/public/geojson/ubicaciones',
                mostrarReferenciasAlturas:true,
                urlReferenciasAlturas:'http://ws.rosario.gov.ar/ubicaciones/public/referenciaalturas',
            },
            minLength: 4,
            pathImg: '/img',
            sinBotonBusqueda: true,
        });

        document.getElementById('export').onclick = function(e) {
            var collection = {
                "type": "FeatureCollection",
                "features": []
            };
            // Iterate the layers of the map
            map.eachLayer(function (layer) {
                // Check if layer is a marker
                if (layer instanceof L.Marker) {
                    // Create GeoJSON object from marker
                    var geojson = layer.toGeoJSON();
                    // Push GeoJSON object to collection
                    collection.features.push(geojson);
                }
            });
            // Log GeoJSON collection to console
            console.log(collection);

            // Stringify the GeoJson
            var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(collection));

            console.log(convertedData);

            // Create export
            document.getElementById('export').setAttribute('href', 'data:' + convertedData);
            document.getElementById('export').setAttribute('download','data.geojson');
        }

        contador = 0;
        //Agregar puntos desde el buscador y convertirlos
        function agregarpuntos(coordenadaX, coordenadaY){
            var firstProjection = "+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs";
            var secondProjection = "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs ";
            conversion = proj4(firstProjection,secondProjection,[coordenadaX,coordenadaY]);

            point = L.marker([conversion[1], conversion[0]]).addTo(drawnItems);
            drawnItems.addLayer(point);
            item = point.toGeoJSON();

            map.setView([item.geometry.coordinates[1],item.geometry.coordinates[0]], 16);

            var layer = point;
            feature = layer.feature = layer.feature || {}; 

            feature.type = feature.type || "Feature"; 
            var props = feature.properties = feature.properties || {}; 

            props.id = contador;
            props.calle = $("#calle").val();

            console.log(item);
            contador++;
        }
    });
</script>

@endsection

