<?php

/**
 * 
 */
class PostCampaign
{
	public static function CampaignTempTable($campaign_id){	
		$temp_table="campaign_$campaign_id";
		// Drop table
		$dropsql = "DROP TABLE IF EXISTS $temp_table";
		$tablequery = Yii::app()->karfdb->createCommand($dropsql)->execute();
		// Recreate the table
		$temp_sql="CREATE TABLE `".$temp_table."` (
		`id` INT  AUTO_INCREMENT PRIMARY KEY ,
		`station_name` varchar(200) not null,
		`uniq_users` longtext  ,
		`audience` float  ,
		`start_time` time  ,
		`end_time` time,
		`session_id` float  ,
		`universe` float  ,
		`mediauniverse` float,
		`trp` float  ,
		`DayWeek` varchar(200),
		`lsm2` varchar(200),
		`Topographies` varchar(200) 
		) ENGINE = InnoDB ";
		$tablequery = Yii::app()->karfdb->createCommand($temp_sql)->execute();
		return $temp_table;
	}
	
	public static function GetDataGuided($plantitle,$gender,$regions,$agelow,$agehigh,$rural_urban,$lsmrange,$mediatype,$session_id,$campaign_table,$start_date,$end_date){
		$conndata = new Dbmethods;
		// $conn = $conndata->KarfDB();
		$outdoor = 0;
		$tv_incidence = 0;
		$radio_incidence = 0;
		$print_incidence = 0;
		$online_incidence = 0;
		$dataarray = array();
		$agelow = (int)$agelow;
		$agehigh = (int)$agehigh;
		$billboardspots = array();
		$data_availability = false;
		if (!is_array($regions)) {
			$regions = explode(',', $regions);
			$regionlist = implode(',', $regions);
		} else {
			$regionlist = implode(',', $regions);
		}
		if(!is_array($mediatype)){
			$mediatype = explode(',', $mediatype);
			$silly = array();
			foreach ($mediatype as $kes) {
				if($kes=='TV'){ $tv_incidence = 1; }
				if($kes=='Radio'){ $radio_incidence = 1; }
				if($kes=='Print'){ $print_incidence = 1; }
				if($kes=='Online'){ $online_incidence = 1; }
				$silly[] = "'".$kes."'";
			}
			$mediatypelist = implode(',', $silly);
		}else{
			$mediatypelist = implode(',', $mediatype);
			$exploded = explode(',', $mediatypelist);
			$silly = array();
			foreach ($exploded as $kes) {
				if($kes=='TV'){ $tv_incidence = 1; }
				if($kes=='Radio'){ $radio_incidence = 1; }
				if($kes=='Print'){ $print_incidence = 1; }
				if($kes=='Online'){ $online_incidence = 1; }
				$silly[] = "'".$kes."'";
			}
			$mediatypelist = implode(',', $silly);
		}
		if($regionlist=='All'){
			$regionquery = " ";
		}else{
			$regionsarray = array();
			foreach ($regions as $keyvalue) {
				$keyvalue = trim($keyvalue);
				$regionsarray[] = "'$keyvalue'";
			}
			$regionlist = implode(',', $regionsarray);
			$regionquery = " AND karf_trackerusers.Topographies IN ($regionlist) ";
		}
		if($gender=='Both' || $gender=='both'){
			$genderquery = " ";
		}else{
			$genderquery = " AND karf_trackerusers.gender ='$gender'";
		}
		if($rural_urban=='both'){
			$ruralurbanquery = " ";
		}else{
			$ruralurbanquery = " AND karf_trackerusers.SETTING ='$rural_urban'";
		}
		if(!is_array($lsmrange)){
			$lsmrange = explode(',', $lsmrange);
		}
		if(!$lsmrange==''){
			$lsmarray = array();
			$outdoorlsmarray = array();
			foreach ($lsmrange as $keyvalue) {
				$keyvalue = trim($keyvalue);
				$outdoorlsmarray[] = $keyvalue;
				$lsmarray[] = "'LSM $keyvalue'";
			}
			$lsmlist = implode(', ', $lsmarray);
			$lsmquery = " AND karf_trackerusers.lsm2 IN ($lsmlist) ";
		}else{
			$lsmquery = " ";
			$outdoorlsmquery = " ";
		}
		// incidences
		if($tv_incidence==1){
			$tvquery = " AND karf_trackerusers.Television_Incidence='Yes' ";
		}else{
			$tvquery = " ";
		}
		if($radio_incidence==1){
			$radioquery = " AND karf_trackerusers.Radio_Incidence='Yes' ";
		}else{
			$radioquery = " ";
		}
		if($print_incidence==1){
			$printquery = " AND karf_trackerusers.Newspaper_Incidence='Yes' ";
		}else{
			$printquery = " ";
		}
		if($online_incidence==1){
			$onlinequery = " AND karf_trackerusers.online_Incidence='Yes' ";
		}else{
			$onlinequery = " ";
		}
		$totaluniverse = 0;
		$targetuniverse_sql = "SELECT karf_trackerusers.UserId AS UserId, SUM(karf_trackerusers.weight) AS audience, karf_trackerusers.weight AS unique_audience
		FROM karf_trackerusers
		WHERE (karf_trackerusers.ExactAGE BETWEEN $agelow AND $agehigh)  
		$tvquery $radioquery $printquery $onlinequery  
		$regionquery $genderquery $ruralurbanquery $lsmquery
		GROUP BY UserId";
		// echo "$targetuniverse_sql <br>";
		// echo "<br>";
		$universedata = Yii::app()->karfdb->createCommand($targetuniverse_sql)->queryAll();
		if($universedata){
			foreach ($universedata as $weightvalues) {
				$userweight = (float)$weightvalues['audience'];
				$totaluniverse = $totaluniverse+$userweight;
			}
			$totaluniverse = (int)$totaluniverse;
			$data_availability = true;
		}
		$mediauniverse = 0;
		$mediauniverse_sql = "SELECT karf_trackerusers.UserId AS UserId, SUM(karf_trackerusers.weight) AS audience
		FROM karf_trackerusers
		WHERE (karf_trackerusers.ExactAGE BETWEEN $agelow AND $agehigh)  
		$regionquery $genderquery $ruralurbanquery $lsmquery
		GROUP BY UserId";
		// echo "$mediauniverse_sql <br>";
		// echo "<br>";
		// echo "<br>";
		$mediauniversedata = Yii::app()->karfdb->createCommand($mediauniverse_sql)->queryAll();
		if($mediauniversedata){
			foreach ($mediauniversedata as $weightvalues) {
				$userweight = (float)$weightvalues['audience'];
				$mediauniverse = $mediauniverse+$userweight;
			}
			$mediauniverse = (int)$mediauniverse;
			$data_availability = true;
		}
		// echo "$mediauniverse <br>";
		$campaign_users = "users_$campaign_table";
		$createql = "CREATE TEMPORARY TABLE $campaign_users AS $targetuniverse_sql";
		$usertableqry = Yii::app()->karfdb->createCommand($createql)->execute();
		// echo "$createql <br>";
		// echo "<br>";

		// get p7d values - targets specific to the days of the week
		$weekdays = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$weekday_values = array();
		foreach ($weekdays as $weekvalue) {
			$daily_unique_audience = 0;
			$uniquep7d = "SELECT karf_trackerusers.UserId AS UserId, SUM(karf_trackerusers.weight) AS audience, karf_trackerusers.DayWeek 
			FROM karf_trackerusers WHERE 
			(karf_trackerusers.ExactAGE BETWEEN $agelow AND $agehigh) 
			$tvquery $radioquery $printquery $onlinequery 
			$regionquery $genderquery $ruralurbanquery $lsmquery 
			AND karf_trackerusers.DayWeek='$weekvalue'
			GROUP BY UserId";
			// echo "$uniquep7d <br>";
			// echo "<br>";
			// run
			$uniquep7d_data = Yii::app()->karfdb->createCommand($uniquep7d)->queryAll();
			if($uniquep7d_data){
				foreach ($uniquep7d_data as $weightvalues) {
					$userweight = (float)$weightvalues['audience'];
					$daily_unique_audience = $daily_unique_audience+$userweight;
				}
				$daily_unique_audience = (int)$daily_unique_audience;
			}
			$daily_overall_audience = 0;
			$overallp7d = "SELECT karf_trackerusers.UserId AS UserId, SUM(karf_trackerusers.weight) AS audience, karf_trackerusers.DayWeek 
			FROM karf_trackerusers WHERE 
			(karf_trackerusers.ExactAGE BETWEEN $agelow AND $agehigh)  
			$regionquery $genderquery $ruralurbanquery $lsmquery
			AND karf_trackerusers.DayWeek='$weekvalue'
			GROUP BY UserId";
			// run
			$overallp7d_data = Yii::app()->karfdb->createCommand($overallp7d)->queryAll();
			if($overallp7d_data){
				foreach ($overallp7d_data as $weightvalues) {
					$userweight = (float)$weightvalues['audience'];
					$daily_overall_audience = $daily_overall_audience+$userweight;
				}
				$daily_overall_audience = (int)$daily_overall_audience;
			}
			$weekday_values[$weekvalue] = array(
				'daily_unique_audience'=>$daily_unique_audience,
				'daily_overall_audience'=>$daily_overall_audience
				);
		}
		// print_r($weekday_values);
		// echo "<br>";
		// echo "<br>";

		$camp_combi = new CampaignCombinations;
		$sql = "SELECT karf_trackerprocessed.station AS station_name,
		GROUP_CONCAT(DISTINCT karf_trackerusers.UserId) AS uniq_users, 
		SUM(karf_trackerusers.weight) AS audience,
		karf_trackerprocessed.start_time,
		karf_trackerprocessed.end_time,
		$session_id AS session_id,
		$totaluniverse AS universe,
		karf_trackerprocessed.DayWeek, 
		karf_trackerusers.lsm2, 
		karf_trackerusers.Topographies 
		FROM karf_trackerprocessed 
		INNER JOIN karf_trackerusers ON karf_trackerusers.UserId=karf_trackerprocessed.UserId
		WHERE (karf_trackerusers.ExactAGE BETWEEN $agelow AND $agehigh)  AND karf_trackerprocessed.DayWeek=karf_trackerusers.DayWeek  
		$tvquery $radioquery $printquery $onlinequery  
		$regionquery $genderquery $ruralurbanquery $lsmquery 
		GROUP BY karf_trackerprocessed.station,karf_trackerprocessed.Dayweek,karf_trackerprocessed.start_time";
		// echo "$sql <br>";
		// echo "<br>";
		$sqlinserts = array();
		$demoresults = Yii::app()->karfdb->createCommand($sql)->queryAll();
		if($demoresults){
			$count = 0;
			foreach ($demoresults as $demovalues) {
				$count++;
				$demo_station_name = $demovalues['station_name'];
				$demo_uniq_users = $demovalues['uniq_users'];
				// calculate spot's potential audience
				$demo_array = explode(',', $demo_uniq_users);
				// $spot_audience = $camp_combi->DistinctUsersReach($demo_array,$campaign_users);
				// $demo_audience = $spot_audience;
				$demo_audience = $demovalues['audience'];
				$demo_start_time = $demovalues['start_time'];
				$demo_end_time = $demovalues['end_time'];
				$demo_session_id = $demovalues['session_id'];
				$demo_universe = $demovalues['universe'];
				// $demo_trp = $demovalues['trp'];
				$demo_DayWeek = $demovalues['DayWeek'];
				$weekday_audience = $weekday_values[$demo_DayWeek]['daily_unique_audience'];
				// calculate spot's potential trp
				$demo_trp = ($demo_audience/$mediauniverse)*100;

				$sqlinserts[] = "('$demo_station_name','$demo_uniq_users','$demo_audience','$demo_start_time','$demo_end_time','$demo_session_id','$totaluniverse','$mediauniverse','$demo_trp','$demo_DayWeek')";
				if(count($sqlinserts)>599){
                    $bulkinsert = PostCampaign::BulkInsert($campaign_table,$sqlinserts);
                    unset($sqlinserts);
                    $sqlinserts = array();
                }
			}
			if(count($sqlinserts)>0){
                $bulkinsert = PostCampaign::BulkInsert($campaign_table,$sqlinserts);
                unset($sqlinserts);
                $sqlinserts = array();
            }
            
			// $ddtest = $camp_combi->UsersPerDayofWeek($campaign_table,$campaign_users);
			// var_dump($ddtest);
		}
		// return whether data is available or not
		return $data_availability;
	}

	public static function BulkInsert($campaign_table,$sqlinserts){
		$multidump = "INSERT INTO `$campaign_table` (station_name,uniq_users,audience,start_time,end_time,session_id,universe,mediauniverse,trp,DayWeek) VALUES ".implode(',', $sqlinserts);
		$dump = Yii::app()->karfdb->createCommand($multidump)->execute();
	}

	public function GetCampaignSpots($campaign,$campaign_table,$model,$start_date,$end_date){
		$runid = $model->id;
		$campaign_users_table = "users_$campaign_table";
		// Get the universe for trp calculations - etc
		$universe_sql = "SELECT universe, mediauniverse FROM $campaign_table LIMIT 1";
		$uni_query = Yii::app()->karfdb->createCommand($universe_sql)->queryRow();
		if($uni_query){
			$universe = $uni_query['universe'];
			$mediauniverse = $uni_query['mediauniverse'];
		}else{
			$universe = 0;
			$mediauniverse = 0;
		}
		// echo $universe;
		$campaignid = $campaign->id;
		// update karf names
		$updatekarfnames = $this->UpdateKarfNames($runid);
		$brandusers = array();
		$amount = 0;
		$spots = 0;
		// for post campaign evaluation we do not need captions because they duplicate audience results
		$brandspots_sql = "SELECT * FROM brandspots WHERE runid = $runid AND entry_type_id!=3";
		$brands_query = Yii::app()->karfdb->createCommand($brandspots_sql)->queryAll();
		if($brands_query){
			foreach ($brands_query as $brandrow) {
				$udata = $this->UpdateSpotsData($brandrow,$campaignid,$universe,$mediauniverse);
				$brandusers[] = $udata['uniq_users'];
				// $amount = $amount + $brandrow['rate'];
				$spots++;
			}
		}
		// for rate card value we take all entry types
		$brandrate_sql = "SELECT SUM(rate) AS rate FROM brandspots WHERE runid = $runid";
		$brandrate_query = Yii::app()->karfdb->createCommand($brandrate_sql)->queryRow();
		if($brandrate_query){
			$amount = $brandrate_query['rate'];
		}

		$grpanalysis = new CampaignCombinations;
		$grp = $grpanalysis->UpdateCombinations($runid,$universe,$mediauniverse,$brandusers,$campaign_users_table);
		// obtain calculated values
		$reach = $grp['combinedreach'];
		$audience = $grp['totalaudience'];
		$frequency = $grp['combinedfrequency'];
		$ggrp = $reach * $frequency;
		$universe = $universe;
		$amount = $amount;
		// delete old results for this run
		$delete_sql = "DELETE FROM run_results WHERE runid = $runid";
		$rs_query = Yii::app()->karfdb->createCommand($delete_sql)->execute();
		// insert new results
		$result_sql = "INSERT IGNORE INTO run_results (runid,spots,reach,audience,frequency,ggrp,universe,mediauniverse,amount,start_date,end_date) 
		VALUES ($runid,$spots,'$reach','$audience','$frequency','$ggrp','$universe','$mediauniverse','$amount','$start_date','$end_date')";
		$rs_query = Yii::app()->karfdb->createCommand($result_sql)->execute();
	}

	public function UpdateKarfNames($runid){
		$sql = "SELECT DISTINCT rf_name FROM brandspots WHERE runid=$runid";
		$rf_query = Yii::app()->karfdb->createCommand($sql)->queryAll();
		if($rf_query){
			foreach ($rf_query as $rfrow) {
				$rfname = $rfrow['rf_name'];
				$karfname = $this->getKarfName($rfname);
				$updatesql = "UPDATE IGNORE brandspots SET karf_name = '$karfname' WHERE rf_name= '$rfname' AND runid=$runid";
				$updateqry = Yii::app()->karfdb->createCommand($updatesql)->execute();
			}
		}
	}

	public function getKarfName($rfname){
		$sql = "SELECT karfname FROM stationmatch WHERE rfname='$rfname'";
		$query = Yii::app()->karfdb->createCommand($sql)->queryRow();
		if ($query) {
			return $query['karfname'];
		}else{
			return '-';
		}
	}

	public function UpdateSpotsData($spotdata,$campaignid,$universe,$mediauniverse){
		// obtain values
		$bspotid = $spotdata['id'];
		$rf_name = $spotdata['rf_name'];
		$rate = $spotdata['rate'];
		$day_name = $spotdata['day_name'];
		$spot_date = $spotdata['spot_date'];
		$spot_time = $spotdata['spot_time'];
		$brand_name = $spotdata['brand_name'];
		$karf_name = $spotdata['karf_name'];
		$getudata = $this->getCampaignUniqueUsers($karf_name,$spot_time,$day_name,$campaignid);
		if($getudata!='-'){
			// $slotid = $getudata['slotid'];
			$audience = $getudata['audience'];
			$uniq_users = $getudata['uniq_users'];
			$trp = ($audience / $universe) * 100;
		}else{
			// $slotid = 0;
			$audience = 0;
			$uniq_users = '-';
			$trp = 0;
		}
		// update spots
		$updatesql = "UPDATE brandspots SET uniq_users='$uniq_users',trp='$trp',audience='$audience',universe='$universe',mediauniverse='$mediauniverse' WHERE id=$bspotid";
		$updatequery = Yii::app()->karfdb->createCommand($updatesql)->execute();
		// package array
		return array('trp'=>$trp, 'audience'=>$audience, 'uniq_users'=>$uniq_users);
	}

	public function getCampaignUniqueUsers($karfstation,$spot_time,$day_name,$campaignid){
		$temp_table="campaign_$campaignid";
		// $temp_table="postcampaign.campaign_$campaignid";
		$sql = "SELECT $temp_table.station_name AS station_name, $temp_table.uniq_users, 
		$temp_table.start_time,
		$temp_table.end_time,$temp_table.DayWeek,$temp_table.audience
		FROM $temp_table 
		WHERE $temp_table.station_name = '$karfstation' AND $temp_table.DayWeek='$day_name'
		AND $temp_table.end_time >= '$spot_time'  AND $temp_table.start_time <= '$spot_time'";
		$query = Yii::app()->karfdb->createCommand($sql)->queryRow();
		if ($query) {
			$afrow = $query;
			return $afrow;
		}else{
			return '-';
		}
	}
}