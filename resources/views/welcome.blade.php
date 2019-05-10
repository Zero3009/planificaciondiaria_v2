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
    <link rel="stylesheet" href="/welcome.css" />
   
</head>
<body id="app-layout">  
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-left">
                <a class="navbar-brand" href="{{ route('welcome') }}"><img src="/img/logo.png" alt="Dispute Bills"></a>
            </div>
            <div class="navbar-right">
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right" style="width: 100%;">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                <li class="dropdown" style="float: right;margin-right: 20px;">
                                    <a href="{{ route('login') }}" style="color: white;"><i class="glyphicon glyphicon-log-in"></i> Iniciar sesión</a>
                                </li>
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

        <div class="container-fluid">

            <div class="col-md-8 col-md-offset-2" style="margin-top: 20px;" >
                <div class="col-lg-4 col-md-4 nb-service-block" id="pagina-1">
                    <div class="nb-service-block-inner">
                        <div class="nb-service-front">
                            <div class="front-content">
                                <i class="glyphicon glyphicon-edit"></i>
                                <h2>Planificación</h3>
                            </div>
                        </div>

                        <div class="nb-service-back">
                            <div class="back-content">
                                <h2>Planificación</h3>
                                <p> En este modulo tendrás la posibilidad de dibujar tus zonas, recorridos y casos puntuales.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 nb-service-block" id="pagina-2">
                    <div class="nb-service-block-inner">
                        <div class="nb-service-front">
                            <div class="front-content">
                                <i class="glyphicon glyphicon-globe"></i>
                                <h2>Mapa tematico</h3>
                            </div>
                        </div>

                        <div class="nb-service-back">
                            <div class="back-content">
                                <h2>Mapa tematico</h3>
                                <p>En este modulo tendrás a disposicion un mapa tematico para visualizar todas las geometrias cargadas por las areas. </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 nb-service-block" id="pagina-3">
                    <div class="nb-service-block-inner">
                        <div class="nb-service-front">
                            <div class="front-content">
                                <i class="glyphicon glyphicon-wrench"></i>
                                <i class="glyphicon glyphicon-cog"></i>
                                <h2>Administración</h3>
                            </div>
                        </div>

                        <div class="nb-service-back">
                            <div class="back-content">
                                <h2>Administración</h3>
                                <p> Este modulo es dedicado a la administración del sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-md-offset-2">
                <!--<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 nb-service-block" id="pagina-5">
                    <div class="nb-service-block-inner">
                        <div class="nb-service-front">
                            <div class="front-content">
                                <i class="glyphicon glyphicon-paste"></i>
                                <h2>Reporte de Problemas</h3>
                            </div>
                        </div>

                        <div class="nb-service-back">
                            <div class="back-content">
                                <h2>Formulario de Reporte de Problemas</h3>
                                <p> En este modulo podras acceder a cargar los formularios de reporte de problemas y luego gestionarlos para su resolución.</p>
                            </div>
                        </div>
                    </div>
                </div>-->

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 nb-service-block" id="pagina-4">
                    <div class="nb-service-block-inner">
                        <div class="nb-service-front">
                            <div class="front-content">
                                <i class="glyphicon glyphicon-cloud"></i>
                                <h2>Servicios SUA</h3>
                            </div>
                        </div>

                        <div class="nb-service-back">
                            <div class="back-content">
                                <h2>Servicios SUA</h3>
                                <p> En este modulo podras acceder a servicios SUA para resolver y/o intervenir solicitudes masivamente.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="/plugins/jQuery/jquery-3.2.1.min.js"></script>
<script src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#pagina-1').click(function() {
        window.location.href = "{{route('planificacion')}}" ;
        return false;
    });
    $('#pagina-2').click(function() {
        window.location.href = "{{route('visualizador')}}";
        return false;
    });
    $('#pagina-3').click(function() {
        window.location.href = "{{route('dashboard')}}" ;
        return false;
    });
    $('#pagina-4').click(function() {
        window.location.href = "{{route('serviciosua')}}" ;
        return false;
    });
    $('#pagina-5').click(function() {
        window.location.href = "{{route('formproblemas')}}" ;
        return false;
    });
});
</script>
</body>
</html>

