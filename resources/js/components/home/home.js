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