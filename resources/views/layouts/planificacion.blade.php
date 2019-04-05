<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico">
    <title>Planificación</title>

    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.min.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.theme.min.css" />
    <link rel="stylesheet" href="/ubicaciones.css" />
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
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

    <script type="text/javascript" src="/plugins/randomColor/randomColor.min.js"></script>
    <script type="text/javascript" src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/plugins/jQuery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/leaflet.draw.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/locate/es_la.js"></script>
    <script type="text/javascript" src="/plugins/leaflet-icon-pulse-master/L.Icon.Pulse.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/easy-button.js"></script>
    <script type="text/javascript" src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/plugins/Leaflet.markercluster/leaflet.markercluster-src.js"></script>
    <script type="text/javascript" src="/plugins/Leaflet.markercluster/leaflet.markercluster.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier-Leaflet/0.2.6/oms.min.js"></script>



    <script type="text/javascript" src="/plugins/Leaflet.markercluster-1.4.1/leaflet.markercluster-src.js"></script>

    <script type="text/javascript" src="/plugins/chosen/chosen.jquery.min.js"></script>

    <script type="text/javascript" src="/ol.js"></script>
    <script type="text/javascript" src="/ubicaciones.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4leaflet.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/tokml.js"></script>
    <script type="text/javascript" src="/list.js"></script>
</head>
<body id="app-layout">  
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">

            <div class="navbar-left">
                <a class="navbar-brand" href="{{ url('/') }}"><img src="/img/logo.png" alt="Rosario igual"></a>
            </div>

            <div class="navbar-header" style="margin-top: 10px;">
                <div id="direcciones" style="position: relative;">
                    <input type="text" class="ubicaciones-input" id="txtDireccionesLugares" style="width: 100%;" placeholder="Direcciones o Lugares. Ej: Italia y Mendoza, Mitre 250" autocomplete="off" {{ (Auth::user()->roles[0]->name == 'administracion') ? 'disabled': '' }}>
                </div>
            </div>

            <div class="navbar-right">
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right" style="width: 100%;">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                <li><a href="{{ url('/login') }}"> Iniciar sesión</a></li>
                            @else
                                <li class="dropdown" style="float: right;margin-right: 20px;">
                                    <a href="{{ url('/logout') }}" style="color: white;"><i class="glyphicon glyphicon-log-out"></i> Cerrar sesión</a> 
                                </li>
                            @endif

                        </ul>          
                </div>
            </div>  
        </div>
    </div>

    <div id="container">
   
        @yield('sidebar')
            

        @yield('content')

    </div>

</body>
</html>
