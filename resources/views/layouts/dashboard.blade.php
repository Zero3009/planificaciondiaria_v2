<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/img/favicon.ico">
    <title>Planificación</title>

    <link rel="stylesheet" href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/estilos.css" />
    <link rel="stylesheet" href="/dashboard.css" />
    <link rel="stylesheet" href="/plugins/d3/billboard.min.css" />

    <script src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
    <script src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script src="/plugins/d3/d3.min.js"></script>
    <script src="/plugins/d3/billboard.min.js"></script>

    
</head>
<body id="app-layout">  
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-left">
                <a class="navbar-brand" href="{{ url('/') }}"><img src="/img/logo.png" alt="Dispute Bills"></a>
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

        @yield('content')

    </div>

</body>
</html>
