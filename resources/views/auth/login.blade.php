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
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Loguear</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}        
        <div class="alert alert-warning" role="alert" id="ocultar">
            <!--MOSTRAR ERROR DE LOGIN -->
                    @if(count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                        </div>
                    @endif 
            <!-- FIN MOSTRAR ERROR -->
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre de usuario</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Loguear
                                </button>
                            </div>
                        </div>
                    </form>
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

