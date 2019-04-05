<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/img/favicon.ico">
    <title>Planificación</title>

<!-- CSS -->
    <!-- Jquery -->
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.min.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.theme.min.css" />
    <!-- Boostrap -->
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    <!--Leaflet -->
    <link rel="stylesheet" href="/plugins/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/plugins/leaflet/easy-button.css" />
    <link rel="stylesheet" href="/plugins/leaflet-icon-pulse-master/L.Icon.Pulse.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/datatables.bootstrap.min.css" />
    <link rel="stylesheet" href="/plugins/datatables/buttons/buttons.dataTables.min.css" />
    <!-- Otros -->
    <link rel="stylesheet" href="/ubicaciones.css" />
    <link rel="stylesheet" href="/estilos.css" />
    <link rel="stylesheet" href="/css/print.css" />

<!-- JAVASCRIPTS -->
    <!-- Jquery -->
    <script src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script src="/plugins/jQuery/jquery-ui.min.js"></script>
    <!-- Boostrap -->
    <script src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <!-- Leaflet -->
    <script src="/plugins/leaflet/leaflet.js"></script>
    <script src="/plugins/leaflet/easy-button.js"></script>
    <script src="/plugins/leaflet-icon-pulse-master/L.Icon.Pulse.js"></script>
    <script src="/plugins/leaflet/proj4.js"></script>
    <script src="/plugins/leaflet/proj4leaflet.js"></script>
    <script src="/plugins/leaflet/tokml.js"></script>
    <script src="/plugins/leaflet.browser.print-master/leaflet.browser.print.min.js"></script>
    <script src="/plugins/FileSaver/FileSaver.js"></script>
    <!-- DataTables -->
    <script src="/plugins/datatables/datatables.min.js"></script>
    <script src="/plugins/datatables/datatables.bootstrap.min.js"></script>
    <script src="/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
    <script src="/plugins/datatables/buttons/jszip.min.js"></script>
    <script src="/plugins/datatables/buttons/buttons.html5.min.js"></script>
    <script src="/plugins/datatables/buttons/pdfmake.min.js"></script>
    <script src="/plugins/datatables/buttons/vfs_fonts.js"></script>
    <script src="/plugins/datatables/buttons/buttons.colVis.min.js"></script>
    <!-- Otro -->
    <script type="text/javascript" src="/ol.js"></script>
    <script type="text/javascript" src="/ubicaciones.js"></script>
</head>
<body id="app-layout">  
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-left">
                <a class="navbar-brand" href="{{ url('/') }}"><img src="/img/logo.png" alt="Dispute Bills"></a>
            </div>
            <div class="navbar-header" style="margin-top: 10px;">
                
                <div id="direcciones" style="width: 70%;float: left;position: relative;">
                    <input type="text" class="ubicaciones-input" id="txtDireccionesLugares" style="width: 100%;" placeholder="Agregar referencias al mapa (calles o direcciones)." autocomplete="off">
                </div>
                <div class="form-group" style="float: right;width: 30%;padding-left: 10px;margin-bottom: 0px;"> 
                    <button class="btn btn-primary" id="limpiar-puntos"> Limpiar</button>
                </div>
            </div>
            <div class="navbar-right">
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right" style="width: 100%;">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                <li class="dropdown" style="float: right;margin-right: 20px;">
                                    <a href="{{ url('/login') }}" style="color: white;"><i class="glyphicon glyphicon-log-in"></i> Iniciar sesión</a>
                                </li>
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
