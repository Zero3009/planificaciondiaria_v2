<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}} ">
    <link rel="shortcut icon" href="/img/favicon.ico">
    <title>Planificación</title>

    <style>
        .list-group-item {
            padding: 0px;
            border: none;
        }
        .card {
            border: none;
        }
        .card-header {
            border: none;
        }
    </style>
</head>
<body id="app-layout">
    <div class="navbar navbar-fixed-top" role="navigation" style="background: #29B6F6">
        <a class="navbar-brand" href="{{ url('/') }}"><img src="/img/logo.png" alt="Dispute Bills"></a>

        @if (Request::segment(2) == "tabla")
            <a href="/formproblemas/" style="color: white;"><h4>Ver formulario</h4></a>
        @else
            <a href="/formproblemas/tabla" style="color: white;"><h4>Ver tabla</h4></a>
        @endif
        <a href="{{ url('/logout') }}" style="color: white;">Cerrar sesión</a> 

    </div>

    <section class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col-12 mx-auto">
                <div id="app">
                    @if (isset($status))
                        <div class="alert alert-success">
                            {{ $status }}
                        </div>
                    @elseif (isset($error))
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('main-content')   
                </div>
            </div>
        </div>
    </section>

    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>