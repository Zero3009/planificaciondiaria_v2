@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-4">
            <div class="card">
                <div class="header">
                    <h4 class="title">Distribución de trabajos</h4>
                    <p class="category">Last Campaign Performance</p>
                </div>
                <div class="content">
                    <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div>

                    <div class="footer">
                        <div class="legend">
                            <i class="fa fa-circle text-info"></i> Open
                            <i class="fa fa-circle text-danger"></i> Bounce
                            <i class="fa fa-circle text-warning"></i> Unsubscribe
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="header">
                    <h4 class="title">Distribución de trabajos</h4>
                    <p class="category">Ultimo mes</p>
                </div>
                <div class="content">
                    <div id="chart-distribucion"></div>
                    <div class="footer">
                        <div class="legend">
                            <i class="fa fa-circle text-info"></i> Open
                            <i class="fa fa-circle text-danger"></i> Click
                            <i class="fa fa-circle text-warning"></i> Click Second Time
                        </div>
                        <hr>
                        <div class="stats">
                            <i class="fa fa-history"></i> Updated 3 minutes ago
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<script type="text/javascript">
$(document).ready( function () {

	$.getJSON("/dashboard/datosindex", function (json) {
        
		var chart = bb.generate({
			bindto: '#chart-distribucion',
		    data: {
		    	json: json,
				keys: {
					x: 'desc',
					value: ['cantidad'],
					
				},
				type: "bar"
		    },
		    axis: {
				x: {
					type: 'category'
				}
			}
		});
    });
	
});
</script>
@endsection

