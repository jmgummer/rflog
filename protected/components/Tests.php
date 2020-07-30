<?php
Class Tests{
	public static function SpendsChart($array,$title,$subtitle,$container_name){
		$axisarray = array();
		$count = 0;
		foreach ($array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['date']))); $count++; }

		$chart_object = array();
		$chart_object['chart'] = array('renderTo' => $container_name,'type' => 'line');
		$chart_object['title'] = array('text' => $title);
		$chart_object['xAxis'] = array('categories' => $axisarray);
		$chart_object['yAxis'] = array(
			array('title'=>array('text'=>'Total Spends','style'=>array('color'=>'#4572A7')),'labels'=>array('style'=>array('color'=>'#4572A7'))),
		);
		$chart_object['legend'] = array('align'=>'right','x'=>- 40,'verticalAlign'=>'top','y'=>20,'floating'=>1,'backgroundColor'=>'white','borderColor'=>'#CCC','borderWidth'=>1,'shadow'=>false);
		$chart_object['plotOptions'] = array('column'=>array('dataLabels'=>array('enabled'=>1,'color'=>'white')));
		/* Print Mentions */
		$Printarray = array();
		$count = 0;
		foreach ($array as $key) {
			if(isset($key["print"])){ $Printarray[$count] = array($key["print"]);  }else{ $Printarray[$count] = 0;  }
			$count++; 
		}
		/* Radio Mentions */
		$Radioarray = array();
		$count = 0;
		foreach ($array as $key) {
			if(isset($key["radio"])){ $Radioarray[$count] = array($key["radio"]);  }else{ $Radioarray[$count] = 0;  }
			$count++; 
		}
		/* TV Mentions */
		$Tvarray = array();
		$count = 0;
		foreach ($array as $key) {
			if(isset($key["tv"])){ $Tvarray[$count] = array($key["tv"]);  }else{ $Tvarray[$count] = 0;  }
			$count++; 
		}
		/* Stocks Data */
		$Totalarray = array();
		$count = 0;
		foreach ($array as $key) {
			if(isset($key["total"])){ $decimal = floatval($key["total"]); $Totalarray[$count] = array($decimal); }else{ $Totalarray[$count] = 0; }
			$count++; 
		}
		$chart_object['series'] = array(
			array('name' => "Print",'color'=>'#00a65a','type' => "column",'data' => $Printarray),
			array('name' => "Radio",'color'=>'#f56954','type' => "column",'data' => $Radioarray),
			array('name' => "TV",'color'=>'#00c0ef','type' => "column",'data' => $Tvarray),
			array('name' => "Total",'color'=>'#AA4643','type' => "column",'data' => $Totalarray)
		);
		$chart_object['credits'] = array('enabled'=>false);
		return json_encode($chart_object);
	}
}