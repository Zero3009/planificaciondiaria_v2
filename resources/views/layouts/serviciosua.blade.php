<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Planificación</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        #drop{
            border:2px dashed #bbb;
            -moz-border-radius:5px;
            -webkit-border-radius:5px;
            border-radius:5px;
            padding:25px;
            text-align:center;
            font:20pt bold,"Vollkorn";color:#bbb
        }
        #b64data{
            width:100%;
        }
        a { text-decoration: none }
    </style>

    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.min.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.theme.min.css" />
    
    <link rel="stylesheet" href="/plugins/font-awesome-4.6.3/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/AdminLTE.css" />
    <link rel="stylesheet" href="/skins/skin-blue.css" />
    <link rel="stylesheet" href="/plugins/select2-4.0.3/css/select2.min.css" />
    <link rel="stylesheet" href="/plugins/datatables/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="/plugins/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/plugins/chosen/chosen-bootstrap.css" />

    <script type="text/javascript" src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/plugins/jQuery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/app.js"></script>
    <script type="text/javascript" src="/plugins/select2-4.0.3/js/select2.full.min.js"></script>
    <script type="text/javascript" src="/plugins/select2-4.0.3/js/i18n/es.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="/plugins/jscolor/jscolor.min.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/proj4leaflet.js"></script>
    <script type="text/javascript" src="/plugins/leaflet/tokml.js"></script>
    <script type="text/javascript" src="/plugins/chosen/chosen.jquery.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.11.1/xlsx.full.min.js"></script> 

</head>

    <body class="skin-blue sidebar-mini">
        <div class="wrapper">
            {{-- HEADER --}}
            <header class="main-header">
                <a href="{{ url('/') }}" class="logo" style="background-color: #0c99ce;">
                    <span class="logo-mini"><b>P</b>YP</span>{{-- mini logo --}}
                     <img src="/img/logo.png" alt="Dispute Bills">
                </a>
                <nav class="navbar navbar-static-top" role="navigation" style="background-color: #00ACEC;">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">              
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i> Cerrar sesión</a></li>
                        </ul>
                    </div>
                </nav>
            </header>
            {{-- SIDEBAR --}}
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="header">PANEL DE CONTROL</li>
                        <li class="active">
                            <a href="/serviciosua"><i class='glyphicon glyphicon-home'></i><span>Home</span></a>
                        </li>
                        <li>
                            <a href="/serviciosua/intervenciones"><i class='glyphicon glyphicon-user'></i> <span>Intervenciones</span></a>
                        </li>
                        <li>
                            <a href="/serviciosua/resoluciones"><i class='glyphicon glyphicon-user'></i> <span>Resoluciones</span></a>
                        </li>
                        <li>
                            <a href="/serviciosua/cargarids"><i class='glyphicon glyphicon-user'></i> <span>Cargar IDs de solicitudes</span></a>
                        </li>
                    </ul>
                </section>
            </aside>
            {{-- CONTENIDO --}}
            <div class="content-wrapper">
                <section class="content">
                    @yield('main-content')
                </section>
            </div>

        </div>
        @yield('js')
    </body>
</html>
