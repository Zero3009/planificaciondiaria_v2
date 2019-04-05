@extends('layouts.welcome')

@section('content')
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
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 nb-service-block" id="pagina-5">
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
        </div>

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

<script type="text/javascript">
$(document).ready( function () {
    $('#pagina-1').click(function() {
        window.location.href = '/planificacion';
        return false;
    });
    $('#pagina-2').click(function() {
        window.location.href = '/visualizador';
        return false;
    });
    $('#pagina-3').click(function() {
        window.location.href = '/admin/dashboard';
        return false;
    });
    $('#pagina-4').click(function() {
        window.location.href = '/serviciosua';
        return false;
    });
    $('#pagina-5').click(function() {
        window.location.href = '/formproblemas';
        return false;
    });
});
</script>
@endsection

