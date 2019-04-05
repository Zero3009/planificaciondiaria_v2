@extends('layouts.administracion')

@section('main-content')

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <h3 class="panel-title">PLANIFICADO EN EL DÍA</h3>
            </div>
            <div class="panel-body"> 
                <label class="control-label col-md-1">Fecha</label>
                <div class="col-md-2">
                    <input placeholder="Fecha:" value="<?php echo \Carbon\Carbon::now()->format('Y-m-d');?>" type="text" class="form-control" id="fecha" readonly="true" style="margin-bottom: 10px;margin-top: -7px">
                </div>
                <table class="table-striped table-bordered" width="100%" id="tablatest">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>Cargó</th>
                            <th>Primera carga</th>
                            <th>Última carga</th>
                            <th>Cantidad de Intervenciones</th>
                            <th>Poligonos</th>
                            <th>Lineas</th>
                            <th>Puntos</th>
                            <th>Detalles</th>
                            
                        </tr>
                    </thead>
                </table>
                        
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container" style="position: relative; height:45vh; width:40vw">
                                <canvas id="myDonut" width="200" height="110"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container" style="position: relative; height:40vh; width:35vw">
                                <canvas id="myPie" width="200" height="110"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <br><hr><br>
                
                    <div class="modal fade" id="tabla" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background: #00ACEC; color: #FFFFFF;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 id="arearea"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="panel panel-default">
                                        <table class="table-striped table-bordered" width="100%" id="tablaArea">
                                            <thead>
                                                <tr>
                                                    <th>Cuadrilla</th>
                                                    <th>Hora</th>
                                                    <th>Area</th>
                                                    <th>Tipo de trabajo</th>
                                                    <th>Horario</th>
                                                    <th>Calle/Zona</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            
            <div class="panel-footer">
                
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" style="background: #4682B4; color: #FFFFFF;">
                <h3 class="panel-title">PLANIFICADO DEL DÍA A 30 DÍAS ATRAS</h3>
            </div>
            <div class="panel-body">
                <canvas id="myChart" width="200" height="100"></canvas>
                <br><hr><br>
                <div class="chart-container" style="position: relative; height:150vh; width:70vw">
                    <canvas id="myInterChart" width="200" height="100"></canvas>
                </div>   
            </div>
            <div class="panel-footer">
            </div>
        </div>
        </div>
    </div>
</div>
<style type="text/css">
.highlight a{
     background-color: #00ACEC !important;
     color: #FFFFFF !important;
}
</style>
@stop

@section('js')
<script>

function hexToRgbA(hex){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1)';
    }
    throw new Error('Bad Hex');
}

function randomColors() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
};

var myChart;
var myDonut;
var myPie;
var myInterChart;
function compare(a,b) {
  if (a.t < b.t)
    return -1;
  if (a.t > b.t)
    return 1;
  return 0;
}
function createChart(fecha){
    if(myChart){
        myChart.destroy();
    }
    if(myDonut){
        myDonut.destroy();
    }
    if(myPie){
        myPie.destroy();

    }
    if(myInterChart){
        myInterChart.destroy();
    }

    var cantidadLineas;
    
    var dats;
    var areas = [];
    var trabajos = [];
    
    var dash = $.getJSON('/datatables/dashgraph', {
        fecha : fecha
    },
    function (data){
        dats = data;
        //console.log(fecha);
        $.getJSON('/datatables/test', {
            fecha : fecha
        },
        function (d){
                var cantidadLineas = d.length;
                var pDataResult= pieee(dats,fecha,d);
                var dData = donuttt(dats, fecha,d);
                console.log(dData);
                var datasets = [];
                var exit = false;
                var oldDate;
                for (var i = 0;i < cantidadLineas; i++) {
                    var linea = {};
                    var flag = false;
                    datasets.push(linea);
                    datasets[i].label = d[i].area;
                    datasets[i].borderColor = hexToRgbA(d[i].color);
                    datasets[i].data = [];
                    datasets[i].backgroundColor = datasets[i].borderColor;
                    datasets[i].fill = false;
                    datasets[i].borderWidth = 3;

                    for(var j = 0;j < dats.length;j++){

                            if(datasets[i].label == dats[j].area){
                                flag = true;
                                var info = {};
                                info.y = dats[j].count;
                                info.t = dats[j].created_at;
                                datasets[i].data.push(info);
                                
                                delete info;
                            }else if(flag == true){
                                //datasets[i].data.push(info);
                                flag = false;
                            }
                    }
                }
                var e = new Date(fecha);
                e.setDate(e.getDate() - 6);

                for(var j = 0;j < datasets.length;j++){
                var d = new Date(e);
                for(var i = 0;i <= datasets[j].data.length && i < 8;i++){
                    var stringyf = d.getFullYear() + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
                    if(i == datasets[j].data.length){
                        var info = {};
                        info.y = "0";
                        info.t = stringyf;
                        
                        datasets[j].data.push(info);
                        datasets[j].data.sort(compare);                        
                    }
                    if(stringyf != datasets[j].data[i].t){

                        var info = {};
                        info.y = "0";
                        info.t = stringyf;
                        
                        datasets[j].data.push(info);
                        datasets[j].data.sort(compare);
                    }
                    d.setDate(d.getDate() + 1);
                    }
                    
                }

                if(d.length != 0){
                    var ctx = document.getElementById("myChart");
                    myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                            datasets: datasets,
                        },
                        options: {
                            responsive: true,
                            legend:{
                                display: true,
                                position: 'right',
                            },
                            title: {
                                    display: true,
                                    text: 'Intervenciones (Ultimos 7 días)',
                                    fontFamily: 'Arial'
                                },
                            scales: {
                                yAxes: [{
                                    gridLines: {
                                        zeroLineColor: '#000000'
                                    },   
                                    ticks: {
                                        beginAtZero:true,
                                        min: 0,
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        zeroLineColor: '#000000'
                                    },
                                    type: 'time',
                                    display: true,
                                    time: {
                                        unit:'day',
                                    },
                                    bounds: 'data'
                                }]
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                            }
                        }

                });
            }
            if (dData[0] == true){
                var xlt = document.getElementById("myDonut");
                myDonut = new Chart(xlt, {
                    type: 'doughnut',
                    data: dData[1],
                    options: {
                            legend:{
                                display: true,
                                position: 'left',
                            },
                            title: {
                                display: true,
                                text: 'Intervenciones por día'
                            }
                    }
                });
            }
            if(pDataResult[1] > 0 || pDataResult[2] > 0 || pDataResult[3] > 0){
                var pie = document.getElementById("myPie");
                myPie = new Chart(pie, {
                    type: 'pie',
                    data: pDataResult[0],
                    options: {
                        legend:{
                            display: true,
                            position: 'right'
                        },
                        title: {
                            display: true,
                            text: 'Figuras por día'
                        }
                    }
                });
            }
            var graphInter = $.getJSON('/graphs/interv',{
                fecha : fecha
            }
            ,function(data){
                if(d.length != 0){
                    var datasets2 = [];
                    var areas = [];
                    for (i = 0;i < data.length;i++){
                        if(i == 0){
                            areas.push(data[i].desc)    
                        }else if(data[i-1].desc != data[i].desc){
                            areas.push(data[i].desc)
                        }
                    }
                    for(i = 0;i < areas.length;i++){
                        var line = {};
                        datasets2.push(line);
                        datasets2[i].data = [];
                        datasets2[i].label = areas[i];
                        datasets2[i].borderColor = randomColors();
                        datasets2[i].backgroundColor = datasets2[i].borderColor;
                        datasets2[i].fill = false;
                        for(j = 0;j < data.length; j++){

                            if(datasets2[i].label == data[j].desc){
                                flag = true;
                                var info = {};
                                info.y = parseInt(data[j].cantidad);
                                info.t = data[j].fecha;
                                datasets2[i].data.push(info);
                            }
                        }

                    }
                    
                    for(var k = 0;k < datasets2.length;k++){
                        var f = new Date(e);
                        for(var l = 0;l <= datasets2[k].data.length && l < 8;l++){
                                var stringyft = f.getFullYear() + '-' + ('0' + (f.getMonth()+1)).slice(-2) + '-' + ('0' + f.getDate()).slice(-2);
                                if(l == datasets2[k].data.length){
                                    var info = {};
                                    info.y = 0;
                                    info.t = stringyft;
                                    
                                    datasets2[k].data.push(info);
                                    datasets2[k].data.sort(compare);                        
                                }
                                if(stringyft != datasets2[k].data[l].t){

                                    var info = {};
                                    info.y = 0;
                                    info.t = stringyft;
                                    
                                    datasets2[k].data.push(info);
                                    datasets2[k].data.sort(compare);
                                }
                                f.setDate(f.getDate() + 1);
                                
                        }
                    }
                    
                    var final = document.getElementById("myInterChart");
                    myInterChart = new Chart(final, {
                    type: 'line',
                    data: {
                            datasets: datasets2,
                        },
                        options: {
                            responsive: true,
                            legend:{
                                display: true,
                                position: 'bottom',
                            },
                            hover: {
                              mode: 'label'
                            },
                            title: {
                                    display: true,
                                    text: 'Intervenciones (Ultimos 7 días)',
                                    fontFamily: 'Arial'
                                },
                            scales: {
                                yAxes: [{
                                    gridLines: {
                                        zeroLineColor: '#000000'
                                    },   
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        zeroLineColor: '#000000'
                                    },
                                    type: 'time',
                                    display: true,
                                    time: {
                                        unit:'day',
                                        beginAtZero:true
                                    },
                                    bounds: 'data'
                                }]
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                            },
                        }

                    });
                }
            });
        });
    });   
}
function pieee(dats, fecha, d){
    var response = [];
    var pDatasets = [];
    var pLabels = [];
    var pObjeto = {};
    var pDataResult = {};
    pDataResult.datasets = [];
    pDataResult.labels = [];
    pDataResult.labels = pLabels;
    pObjeto.data = [];
    pObjeto.backgroundColor = [];
    pPoligonos = 0;
    pLineas = 0;
    pPuntos = 0;
    for(var i = 0;i < dats.length; i++){
        if(dats[i].created_at == fecha){
            pPoligonos = pPoligonos + parseInt(dats[i].poligonos);
            pLineas = pLineas + parseInt(dats[i].lineas);
            pPuntos = pPuntos + parseInt(dats[i].puntos);
        }    
    }
        if(pPoligonos > 0 || pLineas > 0 || pPuntos > 0){
            if (pPuntos > 0){
                pLabels.push('Puntos');
                pObjeto.backgroundColor.push('rgba(0,0,255,1)');
                pObjeto.data.push(pPuntos);       
            }
            if(pPoligonos > 0){
                pLabels.push('Polígonos');
                pObjeto.backgroundColor.push('rgba(0,255,0,1)');
                pObjeto.data.push(pPoligonos);
            }
            if(pLineas > 0){
                pLabels.push('Lineas');
                pObjeto.backgroundColor.push('rgba(255,0,0,1)');
                pObjeto.data.push(pLineas);
            }
            pDatasets = pObjeto;
            pDataResult.datasets.push(pDatasets);
        }
        response.push(pDataResult,pPoligonos,pLineas,pPuntos);
        return response;
}
function donuttt(dats, fecha,d){
    var response = [];
    var create = false;
    var data = [];
                var dflag = false;
                var dDatasets = [];
                var dLabels = [];
                var objeto = {};
                var dData = {};
                dData.datasets = [];
                objeto.data = [];
                objeto.backgroundColor = [];
                var pDataResult = null;
                pDataResult = pieee(dats, fecha);

        for(var i = 0; i< dats.length;i++){
            if(dats[i].created_at == fecha){
                dLabels.push(dats[i].area);   
                data.push(dats[i].count);
                for(var j = 0;j < d.length;j++){
                    if(dats[i].area == d[j].area){
                        objeto.backgroundColor.push(hexToRgbA(d[j].color));
                    }
                }
            }
        }

    if(dLabels.length > 0){
        objeto.data = data;
        dDatasets = objeto;
        dData.datasets.push(dDatasets);
        dData.labels = [];
        dData.labels = dLabels;
        create = true;
    }
    response.push(create);
    response.push(dData);

    return response;
}

window.onload = function(){
    var today = new Date();
    var dt_ddmmyyyy = today.getFullYear() + '-' + ('0' + (today.getMonth()+1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
  createChart(dt_ddmmyyyy);  
} 

$(document).ready( function () {
    var fechas;
    var fecha;
    var flagy = false;

    //CALENDARIO
    $.getJSON('/admin/calendarValidation',
        function(d){
            fechas = d.map(function(e) { return e.fechas; });
                $("#fecha").datepicker({
                    dateFormat: 'yy-mm-dd',
                    todayHighlight: true,
                    beforeShowDay: function(date) {
                        var dt_ddmmyyyy = date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
                        if (fechas.indexOf(dt_ddmmyyyy) != -1) {
                            
                            return [true, "highlight", ''];
                        } else {
                            return [true];
                        }
                    },
                    numberOfMonths: 1,   
                    showAnim: "slideDown",
                    onClose: function(selectedDate) {
                        //console.log(selectedDate);
                        $("#fecha").val(selectedDate);
                        $('#tablatest').DataTable().ajax.reload();
                        createChart($("#fecha").val());
                        flagy = false;
                    },
                    onSelect: function(dateText, inst) {
                        $('#fecha').attr('value',dateText);
                    }
                }).datepicker("setDate", "0");
            });

    var table = $('#tablatest').DataTable({
        "processing": true,
        "serverSide": true,
        ajax: {
            url: '/datatables/dash',
            data: function(d){
                d.fecha = $("#fecha").val();
            },
        },
        "columns":[
            {data: 'areas', name: 'areas'},
            {data: 'cargo', name: 'cargo'},
            {data: 'horaprimera', name: 'horaprimera'},
            {data: 'horaultima', name: 'horaultima'},
            {data: 'cantidad_intervenciones', name: 'cantidad_intervenciones'},
            {data: 'poligonos', name: 'poligonos'},
            {data: 'lineas', name: 'lineas'},
            {data: 'puntos', name: 'puntos'},
            {data: 'action', name: 'action', orderable: false}
            
        ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    if ( aData.cargo == "NO" )
                    {
                        $('td', nRow).css('background-color', 'rgba(255,0,0,0.5)');
                    }
                    else if ( aData.cargo == "SI" )
                    {
                        $('td', nRow).css('background-color', 'rgba(0,255,0,0.5)');
                    }
        },
        "bLengthChange": false,
        "searching": false,
        "bPaginate": false
    });
    
});

var tablaArea;
var flaggg = false;
function myFunction(area){
    $.getJSON('/admin/areaTable',{
        area: area
    },
    function(d){
       document.getElementById('arearea').innerHTML = d[0].desc; 
    });
    
    if(flaggg == true){
        tablaArea.destroy();
    }
    flaggg = true;
    
    tablaArea = $('#tablaArea').DataTable({
        "processing": true,
        "serverSide": true,
        ajax:{
            url:'/datatables/tablaArea',
            data: function(d){
                d.area = area,
                d.fecha = $("#fecha").val()
            }
        },
        "columns":[
            {data: 'descripcion', name: 'descripcion'},
            {data: 'hora', name: 'hora'},
            {data: 'area', name: 'area'},
            {data: 'tipo', name: 'tipo'},
            {data: 'horario', name: 'horario'},
            {data: 'callezona', name: 'callezona'}
        ],
        "language":{
            url: "{!! asset('/plugins/datatables/lenguajes/spanish.json') !!}"
        },
        "bLengthChange": true,
        "searching": false,
        "bPaginate": true,
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    date = new Date($("#fecha").val() + ' ' + aData.hora + ':00');
                    if ((date.getHours() >= 8 && date.getMinutes() > 0) || date.getHours() > 8)
                    {
                        $('td', nRow).css('background-color', 'rgba(255,0,0,0.5)');
                    }
                    else if(date.getHours() == 8 && date.getMinutes() == 0)
                    {
                        $('td', nRow).css('background-color', 'rgba(255,255,0,0.5)');
                    }
                    else
                    {
                        $('td', nRow).css('background-color', 'rgba(0,255,0,0.5)');
                    }
        },
        "dom": 'Bfrtip'
    });

    $("#tabla").modal();
}
</script>
@stop