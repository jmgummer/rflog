<?php
date_default_timezone_set("Africa/Nairobi");
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/**
* AudienceCommand Command Class
* This Class Is Used To Return Audience Values
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Reelmedia
* @subpackage  Commands
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.2.0
* @since       June 2015
*/
class AudienceCommand extends CConsoleCommand
{
	/* Get the Station List */
	public static function StationList($table_name){
		$sql = "SHOW COLUMNS FROM $table_name";
		$field_array = array();
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			foreach ($result as $key) {
				$exceptions = array('id','gp_Day','gp_Time_Block','gp_Total','gp_Sample_Size');
				if(!in_array($key['Field'], $exceptions)){
					$cleaned = str_replace('gp_', '', $key['Field']);
					$field_array[$count]['name'] = $cleaned;
					$field_array[$count]['code']=$key['Field'];
					$count++;
				}
			}
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	public static function MatchedStation($id,$type){
		$sql = "SELECT * FROM geopoll_stations INNER JOIN geopoll_reelforge_stations ON geopoll_reelforge_stations.geopoll_id=geopoll_stations.id 
		WHERE geopoll_reelforge_stations.reelforge_id=$id AND geopoll_stations.station_type=$type;";
		if($model=GeopollStations::model()->findBySql($sql)){
			return $model->station_code;
		}else{
			return false;
		}
	}

	public static function GetPrintAudience($code,$date,$table){
		if($date==date('Y-m-d') || $date==date('Y-m-d', strtotime(' -1 day'))){
			$current_last_week = date('j/n/Y', strtotime('-1 week', strtotime($date))).' - '.date('l', strtotime('-1 week', strtotime($date)));
			$sql = "SELECT $code FROM geopoll_insert_print WHERE gp_Day = '$current_last_week'";
			if($result = Yii::app()->geopolldb->createCommand($sql)->queryRow()){
				$audience = $result[$code];
			}else{
				$audience = '**';
			}
		}else{
			$startdate = date('j/n/Y',strtotime($date)).' - '.date('l',strtotime($date));
			$sql = "SELECT $code FROM geopoll_insert_print WHERE gp_Day = '$startdate'";
			if($result = Yii::app()->geopolldb->createCommand($sql)->queryRow()){
				$audience = $result[$code];
			}else{
				$audience = '**';
			}
		}
		$audience = str_replace(',', '', $audience);
		if($audience=='**' || $audience==0 || $audience==null){
			$audience = GeopollConnect::BlendPrintAudience($code,$date,'geopoll_insert_print');
		}
		return $audience;
	}
	
	public static function BlendPrintAudience($code,$date,$table){
		$grand = 0;
		$counter = 0;
		$weekday = date('l',strtotime($date));
		$sql = "SELECT $code FROM $table WHERE ($code!=0 or $code!=null)  AND gp_day like '%$weekday%' ORDER BY id desc limit 14;";
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			foreach ($result as $key) {
				$audience = $key[$code];
				$audience = str_replace(',', '', $audience);
				$grand = $grand + $audience;
				$counter++;
			}
		}
		// Obtain an average
		if($counter>0){
			$average = $grand/$counter;
		}else{
			$average = '**';
		}
		return $average;
	}

	public static function PullRadioAudience($code,$tablename,$date,$timeblock){
		$startdate = date('j/n/Y',strtotime($date)).' - '.date('l',strtotime($date));
		$check = GeopollConnect::CheckTableColumn($tablename,$code);
		$sql = "SELECT $code FROM $tablename WHERE gp_Day = '$startdate' AND gp_Time_Block = '$timeblock'";
		$result = Yii::app()->geopolldb->createCommand($sql)->queryRow();
		if($result==true && $result[$code]!=0 && $result[$code]!=null && $result[$code]!='**'){
			$audience = $result[$code];
			$audience = str_replace(',', '', $audience);
		}else{
			$grand = 0;
			$counter = 0;
			$weekday = date('l',strtotime($date));
			$sql = "SELECT $code FROM $tablename WHERE ($code!=0 or $code!=null)  AND gp_day like '%$weekday%' AND gp_Time_Block = '$timeblock' order by id desc limit 14;";
			if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
				foreach ($result as $key) {
					$audience = $key[$code];
					$audience = str_replace(',', '', $audience);
					$grand = $grand + $audience;
					$counter++;
				}
			}
			// Obtain an average
			if($counter>0){
				$audience = $grand/$counter;
			}else{
				$audience = '**';
			}
		}
		return $audience;
	}

	public static function CheckTableColumn($table,$column){
		$checksql = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'geopoll_api' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column';";
		if(!$result = Yii::app()->geopolldb->createCommand($checksql)->queryRow()){
			// Add A Check to Make Sure the Table Column Exists
			$qry = "ALTER IGNORE TABLE $table ADD $column VARCHAR(30) DEFAULT '**'"; 
			$altersql = Yii::app()->geopolldb->createCommand($qry)->execute();
			$qry = "ALTER IGNORE TABLE $table ADD gp_Day VARCHAR(30);";
			$altersql = Yii::app()->geopolldb->createCommand($qry)->execute();
			$qry = "ALTER IGNORE TABLE $table ADD gp_Time_Block VARCHAR(30);";
			$altersql = Yii::app()->geopolldb->createCommand($qry)->execute();
		}
	}

	public static function PullTVAudience($code,$tablename,$date,$timeblock){
		$startdate = date('j/n/Y',strtotime($date)).' - '.date('l',strtotime($date));
		$check = GeopollConnect::CheckTableColumn($tablename,$code);
		$sql = "SELECT $code FROM $tablename WHERE gp_Day = '$startdate' AND gp_Time_Block = '$timeblock'";
		$result = Yii::app()->geopolldb->createCommand($sql)->queryRow();
		if($result==true && $result[$code]!=0 && $result[$code]!=null && $result[$code]!='**'){
			$audience = $result[$code];
			$audience = str_replace(',', '', $audience);
		}else{
			$grand = 0;
			$counter = 0;
			$weekday = date('l',strtotime($date));
			$sql = "SELECT $code FROM $tablename WHERE ($code!=0 or $code!=null)  AND gp_day like '%$weekday%' AND gp_Time_Block = '$timeblock' order by id desc limit 14;";
			if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
				foreach ($result as $key) {
					$audience = $key[$code];
					$audience = str_replace(',', '', $audience);
					$grand = $grand + $audience;
					$counter++;
				}
			}
			// Obtain an average
			if($counter>0){
				$audience = $grand/$counter;
			}else{
				$audience = '**';
			}
		}
		return $audience;
	}

	public static function RadioStationList($table_name){
		$sql = "SHOW COLUMNS FROM $table_name";
		$field_array = array();
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			foreach ($result as $key) {
				$exceptions = array('id','gp_Day','gp_Time_Block','gp_Total','gp_Sample_Size');
				if(!in_array($key['Field'], $exceptions)){
					$cleaned = str_replace('gp_', '', $key['Field']);
					$field_array[$count]['name'] = $cleaned;
					$field_array[$count]['code']=$key['Field'];
					$count++;
				}
			}
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	public static function NewspaperList($table_name){
		$sql = "SHOW COLUMNS FROM $table_name";
		$field_array = array();
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			foreach ($result as $key) {
				$exceptions = array('id','gp_Day','gp_Time_Block','gp_Total','gp_Sample_Size');
				if(!in_array($key['Field'], $exceptions)){
					$cleaned = str_replace('gp_', '', $key['Field']);
					$field_array[$count]['name'] = $cleaned;
					$field_array[$count]['code']=$key['Field'];
					$count++;
				}
			}
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	/* Get Time Bands */
	public static function TimeBandList($table_name){
		$sql = "select distinct gp_Time_Block from geopoll_insert_tv;";
		$field_array = array();
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			foreach ($result as $key) {
				$field_array[$count]['block'] = $key['gp_Time_Block'];
				$count++;
			}
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $field_array;
	}

	public static function RadioTimeBandList($table_name){
		$sql = "select distinct gp_Time_Block from geopoll_insert_radio;";
		$field_array = array();
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			foreach ($result as $key) {
				$field_array[$count]['block'] = $key['gp_Time_Block'];
				$count++;
			}
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $field_array;
	}

	/* Generate Station Dropdown */
	// It would be easier to use cHtmList to get options but its problematic, so moving on

	public static function RadioStationDropDown($form,$model){
		if($data = GeopollConnect::RadioStationList('geopoll_insert_radio')){
			$select = "<select class='chosen-select form-control stationselects' name='geopollstations' multiple='multiple' id='geopollstations'  'required'>";
			foreach ($data as $key) {
				$code = $key['code'];
				$name = $key['name'];
				$select.= "<option value='$code'>$name</option>";
			}
			$select.= "</select>";
			echo $select;
		}else{
			echo 'No Station';
		}
		
	}

	public static function StationDropDown($form,$model){
		if($data = GeopollConnect::StationList('geopoll_insert_tv')){
			$select = "<select class='chosen-select form-control stationselects' name='geopollstations' multiple='multiple' id='geopollstations'  'required'>";
			foreach ($data as $key) {
				$code = $key['code'];
				$name = $key['name'];
				$select.= "<option value='$code'>$name</option>";
			}
			$select.= "</select>";
			echo $select;
		}else{
			echo 'No Station';
		}
		
	}

	/* Generate Time Block Dropdown */

	public static function TimeBandDropDown($form,$model,$name){
		if($data = GeopollConnect::TimeBandList('geopoll_insert_tv')){
			$select = "<select class='form-control' name='$name' id='$name' 'required'>";
			foreach ($data as $key) {
				$code = $key['block'];
				$name = $key['block'];
				$select.= "<option value='$code'>$name</option>";
			}
			$select.= "</select>";
			echo $select;
		}else{
			echo 'No Station';
		}
	}

	public static function RadioTimeBandDropDown($form,$model,$name){
		if($data = GeopollConnect::RadioTimeBandList('geopoll_insert_radio')){
			$select = "<select class='form-control' name='$name' id='$name' 'required'>";
			foreach ($data as $key) {
				$code = $key['block'];
				$name = $key['block'];
				$select.= "<option value='$code'>$name</option>";
			}
			$select.= "</select>";
			echo $select;
		}else{
			echo 'No Station';
		}
	}

	/* Build Station SQL */

	public static function GetData($fields,$day,$timeblock,$timeblock2,$start,$stop){
		$dataset = GeopollConnect::StationDataSelects($fields,$day,$timeblock,$timeblock2,$start,$stop);
		$data = GeopollConnect::DataQuery($dataset);
		return $data;
	}

	public static function GetRadioData($fields,$day,$timeblock,$timeblock2,$start,$stop){
		$dataset = GeopollConnect::RadioStationDataSelects($fields,$day,$timeblock,$timeblock2,$start,$stop);
		$data = GeopollConnect::DataQuery($dataset);
		return $data;
	}

	/* Build Station SQL */

	public static function StationDataSelects($fields,$day,$timeblock,$timeblock2,$start,$stop){
		$data_array = array();
		if(is_array($fields)){ $field_query = implode(",", $fields); }else{ $field_query = ''; }
		$startdate = date('d/m/Y',strtotime($start)).' - '.date('l',strtotime($start));
		$enddate = date('d/m/Y',strtotime($stop)).' - '.date('l',strtotime($stop));
		$set = "gp_Day, gp_Time_Block, $field_query, gp_Total, gp_Sample_Size";
		$sql = "SELECT $set FROM geopoll_insert_tv 
		WHERE gp_Day BETWEEN '$startdate' AND '$enddate' AND gp_Time_Block between '$timeblock' AND '$timeblock2';";
		$data_array['sql']=$sql;
		$data_array['fields']=$set;
		return $data_array;
	}

	public static function RadioStationDataSelects($fields,$day,$timeblock,$timeblock2,$start,$stop){
		$data_array = array();
		if(is_array($fields)){ $field_query = implode(",", $fields); }else{ $field_query = ''; }
		$startdate = date('d/m/Y',strtotime($start)).' - '.date('l',strtotime($start));
		$enddate = date('d/m/Y',strtotime($stop)).' - '.date('l',strtotime($stop));
		$set = "gp_Day, gp_Time_Block, $field_query, gp_Total, gp_Sample_Size";
		$sql = "SELECT $set FROM geopoll_insert_radio  
		WHERE gp_Day BETWEEN '$startdate' AND '$enddate' AND gp_Time_Block between '$timeblock' AND '$timeblock2';";
		$data_array['sql']=$sql;
		$data_array['fields']=$set;
		return $data_array;
	}

	/* Get the Stations and Data 
	** Get Key Elements
	** Build Table 
	*/

	public static function DataQuery($array){
		$fields = explode(',', $array['fields']);
		$sql = $array['sql'];
		if($result = Yii::app()->geopolldb->createCommand($sql)->queryAll()){
			$count = 0;
			$field_array = GeopollConnect::DynamicTable($fields);
			foreach ($result as $key) {
				/**/
				$field_array .= "<tr>";
				while (($name = current($key)) !== FALSE ) {
					$arraykey = key($key);
					$keyvalue = $key[$arraykey];
					$field_array .= "<td>".$keyvalue."</td>";
					next($key);
				}
				$field_array .= "</tr>";
			}
			$field_array .= '</table>';
			$result = $field_array;
		}else{
			$result = FALSE;
		}
		return $result;
	}

	/* Station Table Head */

	public static function DynamicTable($fields){
		$tablehead = "<table class='table table-striped'><tr>";
		foreach ($fields as $key => $value) {
			$value = str_replace('gp_', '', $value);
			$value = str_replace('_', ' ', $value);
			$tablehead .=  '<td><strong>'.$value.'</strong></td>';
		}
		$tablehead .=  "</tr>";
		return $tablehead;
	}

	/* Generate Radio Time */

	public static function RadioTime($time){
		$date = strtotime($time);
		$hour = date('H:00', $date);
		if ($hour % 2 == 0) {
			$startblock = $hour;
			$endblock = date('H:00', strtotime('+2 hours', strtotime($startblock)));
		}else{
			$startblock = date('H:00', strtotime('-1 hours', strtotime($hour)));
			$endblock = date('H:00', strtotime('+1 hours', strtotime($hour)));
		}
		$timeblock = $startblock.' - '.$endblock;
		return $timeblock;
	}

	public static function TVTime($time){
		$date = strtotime($time);
		$minute = date('i', $date);
		if($minute<15){
			$timeblock = date('H:00', $date);
		}
		if($minute>=15 && $minute<=30){
			$timeblock = date('H:00', $date);
		}
		if($minute>=30 && $minute<=59){
			$timeblock = date('H:30', $date);
		}
		return $timeblock;
	}
}
