<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Planificación</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.min.css" />
    <link rel="stylesheet" href="/plugins/jQuery/jquery-ui.theme.min.css" />
    

    <link rel="stylesheet" href="/plugins/font-awesome-4.6.3/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    
    <link rel="stylesheet" href="/AdminLTE.css" />
    <link rel="stylesheet" href="/skins/skin-blue.css" />
    <link rel="stylesheet" href="/plugins/select2-4.0.3/css/select2.min.css" />
    <link rel="stylesheet" href="/plugins/datatables/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="/css/mySwitch.css">
    <link rel="stylesheet" href="/plugins/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/plugins/chosen/chosen-bootstrap.css" />

    <script type="text/javascript" src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/plugins/jQuery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/bootstrap-switch.min.js"></script>
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
    <script type="text/javascript" src="/plugins/Chart.bundle.js"></script>
    <script type="text/javascript" src="/plugins/datatables/datatables.bootstrap.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.2/xlsx.full.min.js"></script> 
    
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
                            <li><a class="dropdown-item" style="color: white;" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="glyphicon glyphicon-log-out"></i> Cerrar sesión</a>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form> 
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            {{-- SIDEBAR --}}
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu" id= "ui">
                        <li class="header">PANEL DE CONTROL</li>
                        <li class="{{ Request::segment(2) === 'dashboard' || Request::segment(3) === 'dashboard' ? 'active' : null }}">
                            <a href="{{route('dashboard')}}"><i class='glyphicon glyphicon-dashboard'></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'usuarios' || Request::segment(3) === 'usuarios' ? 'active' : null }}">
                            <a href="{{route('usuarios')}}"><i class='glyphicon glyphicon-user'></i> <span>Gestionar usuarios</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'etiquetas' || Request::segment(3) === 'etiquetas' ? 'active' : null }}">
                            <a href="{{route('etiquetas')}}"><i class='glyphicon glyphicon-tag'></i> <span>Gestionar etiquetas</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'estilos' || Request::segment(3) === 'estilos' ? 'active' : null }}">
                            <a href="{{route('estilos')}} "><i class='glyphicon glyphicon-tint'></i> <span>Gestionar estilos</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'areasconfig' || Request::segment(3) === 'areasconfig' ? 'active' : null }}">
                            <a href="{{route('areasconfig')}}"><i class='glyphicon glyphicon-pawn'></i> <span>Gestionar areas</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'capasutiles' || Request::segment(3) === 'capasutiles' ? 'active' : null }}">
                            <a href="{{route('capasutiles')}}"><i class='glyphicon glyphicon-pawn'></i> <span>Gestionar capas utiles</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'importar' || Request::segment(3) === 'importar' ? 'active' : null }}">
                            <a href="{{route('importar')}}"><i class='glyphicon glyphicon-export'></i> <span>Importar</span></a>
                        </li>
                        <li class="{{ Request::segment(2) === 'datoscomplementarios' || Request::segment(3) === 'datoscomplementarios' ? 'active' : null }}">
                            <a href="{{route('datoscomplementarios')}}"><i class= 'glyphicon glyphicon-export'></i><span>Datos complementarios</span></a>
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
<script>