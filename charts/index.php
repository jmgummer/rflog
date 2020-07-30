<?php
$config_destination = "config/auto_load.php";
require_once $config_destination;
$chart = new Highchart();
$chart->chart = array('renderTo' => 'container','type' => 'pie','marginRight' => 130,'marginBottom' => 25);
$chart->title = array('text' => 'Title','x' => - 20);
$chart->subtitle = array('text' => 'Subtitle','x' => - 20);
// $chart->xAxis->categories = array('Jan','Feb','Mar','Apr');
// $chart->yAxis = array('title' => array('text' => 'Temperature (°C)'),'plotLines' => array(array('value' => 0,'width' => 1,'color' => '#808080')));
$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
$chart->series[] = array('name' => 'Tokyo','data' => array(14.5));
$chart->series[] = array('name' => 'New York','data' => array(2.5));
// $chart->series[] = array('name' => 'Berlin','data' => array(- 0.9,0.6,3.5,8.4,13.5,17.0,18.6,17.9,14.3,9.0,3.9,1.0));
// $chart->series[] = array('name' => 'London','data' => array(3.9,4.2,5.7,8.5,11.9,15.2,17.0,16.6,14.2,10.3,6.6,4.8));
$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+ this.y +'°C';}");
$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
$chart->includeExtraScripts(array('export'));


// $chart = new Highchart();
// $chart->includeExtraScripts();

$chart->chart->renderTo = "container";
$chart->chart->plotBackgroundColor = null;
$chart->chart->plotBorderWidth = null;
$chart->chart->plotShadow = false;
$chart->title->text = "Browser market shares at a specific website, 2010";
$chart->tooltip->pointFormat = '{series.name}: <b>{point.percentage:.1f}%</b>';
$chart->subtitle->text = "Observed in Vik i Sogn, Norway, 2009";
$chart->xAxis->categories = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$chart->yAxis->title->text = 'Temperature ( °C )';
$chart->plotOptions->pie = array(
    'allowPointSelect' => true,
    'cursor' => 'pointer',
    'dataLabels' => array(
        'enabled' => true,
        'color' => '#000000',
        'connectorColor' => '#000000',
        'formatter' => new HighchartJsExpr("function () {
            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %'; }")
    )
);

$chart->series[] = array(
    'type' => 'pie',
    'name' => 'Browser share',
    'data' => array(
        array('Firefox', 45.0),
        array('IE', 26.8),
        array(
            'name' => 'Chrome',
            'y' => 12.8,
            'sliced' => true,
            'selected' => true
        ),
        array('Safari', 8.5),
        array('Opera', 6.2),
        array('Others', 0.7)
    )
);


?>

<html>
    <head>
        <title>Basic Line</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php $chart->printScripts(); ?>
    </head>
    <body>
        <div id="container"></div>
        <script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>
    </body>
</html>