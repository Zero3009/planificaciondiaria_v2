<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
    <link rel="stylesheet" href="/css/pdf.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.min.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.theme.min.css" />
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/plugins/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/plugins/leaflet.route.machine/leaflet-routing-machine.css" />

    <script type="text/javascript" src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/plugins/jQuery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4leaflet.js"></script>
    <script src="/plugins/leaflet.route.machine/leaflet-routing-machine.min.js"></script>
  </head>
  <body>

    <div class="container-fluid">
      <h1>Sistema de Planificación y Logistica {{ $date }}</h1>
      <p>Resize the browser window to see the effect. {{ $coleccion }}</p>
      <p>The columns will automatically stack on top of each other when the screen is less than 768px wide.</p>
      <div class="row">
        <div id="map" class="map leaflet-container leaflet-fade-anim"></div>
      </div>
    </div>
  </body>
  <script>
    $(document).ready( function () {

        //Predefinimos variables
        EPSG900913 = new L.Proj.CRS('EPSG:900913','+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext +no_defs');

        //Variable que contiene el mapa
        map = new L.Map('map', {center: new L.LatLng(-32.9497106, -60.6473459), zoom: 12, minZoom: 12, zoomControl:false}),

        //Setiar limites
        map.setMaxBounds([
            [-33.066801, -60.856018],
            [-32.817864, -60.493469]
        ]);

        L.tileLayer.wms('http://pyp-svr.pyp.rosario.gov.ar/mapproxy/service?', {
            layers: ['osm'],
            format: 'image/jpeg',
            crs: EPSG900913
        }).addTo(map);

        function enrutar(waypoints, type){
            if(type == "bypolygon"){
                coleccion = waypoints;
                coleccion.unshift(markerselected[0].geom);
                coleccion.push(markerselected[1].geom);
            }
            else if (type == "byselect") {
                var array = []; //new Array(); 
                $.each(waypoints, function( key, value ) {
                    array.push(value.geom);
                });
                coleccion = array;
            }

            return L.Routing.control({
                waypoints: coleccion,
                routeWhileDragging: true,
                language: 'es',
                showAlternatives: true,
                fitSelectedRoutes: false,
                createMarker: function (i, start, n){
                    var marker_icon = null
                    if (i == 0) {
                        // This is the first marker, indicating start
                        marker_icon = startIcon
                    } else if (i == n -1) {
                        //This is the last marker indicating destination
                        marker_icon = destinationIcon
                    } else {
                        marker_icon = intermedioIcon
                    }
                    var marker = L.marker (start.latLng, {
                        draggable: true,
                        bounceOnAdd: false,
                        bounceOnAddOptions: {
                            duration: 1000,
                            height: 800
                        },
                        icon: marker_icon
                    })
                    return marker
                },
                // Show the routing icon on a reloaded window
                show: true,
                // Enable the box to be collapsed
                collapsible: true,
                // Collapse button which opens the routing icon (mouse over)
                // Fix this so the routing box closes when mouse leaves the routing window rather than the window "X"
                collapseBtn: function(itinerary) {
                    var collapseBtn = L.DomUtil.create('span', itinerary.options.collapseBtnClass);
                    L.DomEvent.on(collapseBtn, 'click', itinerary._toggle, itinerary);
                    itinerary._container.insertBefore(collapseBtn, itinerary._container.firstChild);
                },
            })
            .on('routesfound', function(e) {
                map.removeControl(drawControl);
                $(".loading").attr("style", "display: none;");
                importItems.clearLayers();
                map.removeControl(enrutar_puntos);
            })
            .on('routingstart', function(){
                $(".loading").attr("style", "display: block;");
            })
            .on('routingerror', function(){
                $(".loading").attr("style", "display: none;");
                console.log("error");
            });
        }

    });
</script>
</html>