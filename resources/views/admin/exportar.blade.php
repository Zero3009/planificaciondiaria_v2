@extends('layouts.administracion')

@section('main-content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <div class="row">
                    <div class="col-md-2" style="float: left;">
                        <h3 class="panel-title" style="margin-top: 10px;"><b>Exportar planificación</b></h3>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label col-sm-4">Fecha desde:</label>
                        <div class="col-md-8">     
                            <input placeholder="Desde:" readOnly required value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="min">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label col-sm-4">Hasta:</label>
                        <div class="col-md-8">     
                            <input placeholder="Hasta:" readOnly required value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="max">
                        </div>
                    </div>
                    <div class="col-md-4" style="float: right;">
                        <button id="buscar" class="btn btn-primary" style="width: 100%"><i class="glyphicon glyphicon-filter"></i> Buscar</button>
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
				<div id="map" style="width: 100%; height: 600px;"></div>
            </div>
            <div class="panel-footer">
                    <a class="btn btn-success" href="#" id="export">
                    <i class="glyphicon glyphicon-export"></i> Exportar a KML</a>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
    </div>
</div>

@stop

@section('js')

<script>


  $(document).ready( function () {
//Predefinimos variables
    osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18, attribution: '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors'});
    EPSG22185 = new L.Proj.CRS('EPSG:22185', '+proj=tmerc +lat_0=-90 +lon_0=-60 +k=1 +x_0=5500000 +y_0=0 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');

    //Iniciar mapa
    map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, minZoom: 12, closePopupOnClick: false});

    //Setear limites del mapa
    map.setMaxBounds([
        [-33.066801, -60.856018],
        [-32.817864, -60.493469]
    ]);
    
    //Definimos grupos
    importItems = L.featureGroup();
    map.addLayer(importItems);

    //Añadimos controles
    L.control.layers({
        'OpenStreetMap':osm.addTo(map),
    },
	{'Capa':importItems}, { position: 'topright', collapsed: true }).addTo(map);

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

    layer.bindPopup(sololectura);
}

//Cargar Geojson con las geometria del dia de la fecha
cargarJSON();

function cargarJSON(){
    importItems.clearLayers();
    rol = "administracion";

    $.getJSON("/ajax/estilo_capa", function (estilos) {
        var data = {
            "fecha": $('#min').val(),
            "fecha_hasta": $('#max').val(),
            "rol": rol,
            "_token": $('input[name=_token]').val()
        };
        $.ajax({
            type:"POST",
            url:'/ajax/puntos',
            data: data,
            dataType: 'json',
            success: function(response) {
                geojson = jQuery.parseJSON(response[0].row_to_json);
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
        $.ajax({
            type:"POST",
            url:'/ajax/poligonos',
            data: data,
            dataType: 'json',
            success: function(response) {
                geojson = jQuery.parseJSON(response[0].row_to_json);
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

        $.ajax({
            type:"POST",
            url:'/ajax/lineas',
            data: data,
            dataType: 'json',
            success: function(response) {
                geojson = jQuery.parseJSON(response[0].row_to_json);
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
    });
}

$("#buscar").click(function(e){
    cargarJSON();
});

document.getElementById('export').onclick = function(e) {
    var geojson = [];
    importItems.eachLayer(function (layer) {
        layergeo = layer.toGeoJSON().features;
        $.each( layergeo, function( key, value ) {
            geojson.push(value);
        });
    });

    //console.log(geojson);
    var featureCollection = {
        "type": "FeatureCollection",
        "features": geojson
    };

    var kmlNameDescription = tokml(featureCollection, {
        name: 'callezona',
        description: 'descripcion',
        documentName: 'Sistema de Planificación Diaria'
    });
    // Stringify the GeoJson
    var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(kmlNameDescription);
    // Create export
    document.getElementById('export').setAttribute('href', 'data:' + convertedData);
    document.getElementById('export').setAttribute('download','data.kml');
}

$(function() {
    var currentTime = new Date();
    // First Date Of the month 
    var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth() -2,1);
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

}); 


</script>

@stop
