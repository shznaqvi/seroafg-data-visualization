  <?php
 session_start();
if (!isset($_SESSION['username']))
header("Location:login.php?location=" . urlencode($_SERVER['REQUEST_URI'])); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	 <script type="text/javascript">

   // Load the Visualization API and the controls package.
      google.charts.load('visualization', '1.1', {'packages':['corechart', 'controls', 'table']});

	  
	  function resetStyling(id) {
    $('#' + id + ' table')
        .removeClass('google-visualization-table-table')
        .addClass('table table-bordered table-condensed table-striped table-hover');
    var parentRow = $('#' + id + ' td.TotalCell').parent();
    parentRow.addClass('TotalRow');
}

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawDashboard);

      // Callback that creates and populates a data table,
      // instantiates a dashboard, a range slider and a pie chart,
      // passes in the data and draws it.
      function drawDashboard() {
      var jsonData = $.ajax({
          url: "./data/prov_status.php",
          dataType: "json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
	
		    // Create a dashboard.
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('dashboard_div'));

        // Create a range slider, passing some options
        var donutRangeSlider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div',
          'options': {
            'filterColumnLabel': 'Total Enrollment'
          }
        });

        // Create a pie chart, passing some options
        var pieChart = new google.visualization.ChartWrapper({
          'chartType': 'ColumnChart',
          'containerId': 'chart_div',
          'options': {
		  title:"Sero Afghanistan Summary",
          legend: 'none',
           width: '100%',
            height: '100%',
           colors: ['#000000'],
           series: {
                  0: { color: '#06b4c8' }, 
                  1: { color: '#d4e157' }, 
                  2: { color: '#ffca28' }, 
                  3: { color: '#ff7043' }, 
                  4: { color: '#bdbdbd' }, 
                  5: { color: '#26c6da' }, 
              },
        legend: {position: 'top right', textStyle: {fontSize: 10}},
        chartArea: {width: '66%'},
          		  
                },    // Draw a trendline for data series 0.
   view: {columns: [0,1,2,3,4,5]}
   });
    
	var cssClassNames = {
'headerRow': 'italic-darkblue-font large-font bold-font',
'tableRow': '',
'oddTableRow': 'beige-background',
'selectedTableRow': 'orange-background large-font',
'hoverTableRow': '',
'headerCell': 'gold-border',
'tableCell': '',
'rowNumberCell': 'underline-blue-font'};
	
		//pieChart.setColumns([1,2]);
// Create a pie chart, passing some options
       var table = new google.visualization.ChartWrapper({
			'chartType': 'Table',
			'containerId': 'table_div',
						width: '100%', height: '100%',
						   view: {columns: [0,1,2,3,4,5,8,9]},
'allowHtml': true		   

        });
		// ===============
	
		function getSum(data, column) {
    var total = 0;
    for (i = 0; i < data.getNumberOfRows(); i++)
      total = total + data.getValue(i, column);
    return total;
  }
		
		// ===============
		data.addRow([{v: 'Total', p: {className: 'TotalCell'}}, getSum(data, 1), getSum(data, 2), getSum(data, 3), getSum(data, 4), getSum(data, 5), null,null,getSum(data, 8), getSum(data, 9)],{style: 'font-style:bold; font-size:22px;'});


		
		
		
		
		
        // Establish dependencies, declaring that 'filter' drives 'pieChart',
        // so that the pie chart will only display entries that are let through
        // given the chosen slider range.
        dashboard.bind(donutRangeSlider, [pieChart, table]);

		
        // Draw the dashboard.
        dashboard.draw(data);
			//sorting event
			//add the listener events
 
	google.visualization.events.addListener(dashboard, 'ready', function() {
        google.visualization.events.addListener(table.getChart(), 'sort', function(ev) {
//alert("Hello! I am an alert box!!");	
        //find the last row
        var parentRow = $('#table_div td.TotalCell').parent();
        //set the TotalRow row to the last row again.
        if (!parentRow.is(':last-child')) {
            parentRow.siblings().last().after(parentRow);
        }

        //reset the styling of the table
        //resetStyling('table_div');
    }); 
	  

				
			});
		
		/* google.visualization.events.addListener(table, 'sort', function (ev) {     
alert("Hello! I am an alert box!!");	
        //find the last row
        var parentRow = $('#table_div td.TotalCell').parent();
        //set the TotalRow row to the last row again.
        if (!parentRow.is(':last-child')) {
            parentRow.siblings().last().after(parentRow);
        }

        //reset the styling of the table
        resetStyling('table_div');
    }); */
  
			
   
	   
      }
	  $(window).resize(function(){
        drawDashboard();
        }); 

    </script>
    <link rel="icon" href="../../favicon.ico">

    <title>Sero Afghanistan: Home</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">
 <link href="../css/bootstrap-material-design.css" rel="stylesheet">
  <link href="../css/ripples.min.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
	#table_div tr:last-child {
    font-weight: bold;
}
	</style>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
	  
        <div class="navbar-header">
		    

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Polio Seropervalence Survey Aghanistan</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
            <!--<li><a href="enrollments.php">Enrollments</a></li>-->
            <li><a href="./app">Downloads</a></li>
          </ul>
         
        </div>
		
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div class="alert alert-info" role="alert">
  <strong>Welcome!</strong>  <?php echo $_SESSION['username'];?>
</div>
	 <h1 class="page-header">Dashboard</h1>
		  <div id="filter_div" style="display:none"></div>

          <div class="row placeholders">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 placeholder">
             <div id="chart_div" style="width:100%;height:100%"></div>
            </div>
          </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 placeholder">
          <h2 class="sub-header">Summary Table</h2>
          <div id="table_div" class="table-responsive" style="font-size:8px">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

<!-- Material Design for Bootstrap -->
<script src="../js/material.js"></script>
<script src="../js/ripples.min.js"></script>
<script>
  $.material.init();
</script>

<!-- Sliders -->
<script src="//cdnjs.cloudflare.com/ajax/libs/noUiSlider/6.2.0/jquery.nouislider.min.js"></script>

<!-- Dropdown.js -->
<script src="https://cdn.rawgit.com/FezVrasta/dropdown.js/master/jquery.dropdown.js"></script>
<script>
  $("#dropdown-menu select").dropdown();
</script>
  </body>
</html>
