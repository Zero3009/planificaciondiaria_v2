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
    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')
    </style>
    
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
                                    <a class="dropdown-item" style="color: white;" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="glyphicon glyphicon-log-out"></i> Cerrar sesión</a>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form> 
                                </li>
                            @endif

                        </ul>          
                </div>
            </div>  
        </div>
    </div>

    <div id="container">

        @yield('content')

    </div>

    {{-- Scripts --}}
    <!-- Jquery -->
    <script src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script src="/plugins/jQuery/jquery-ui.min.js"></script>
    <!-- Boostrap -->
    <script src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    @yield('footer_scripts')
</body>
</html>
