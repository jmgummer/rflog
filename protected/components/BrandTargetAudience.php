<?php

/**
 * For planning db
 */
class BrandTargetAudience
{
	/* Create Temp table for the campaign */
	// public function CampaignTempTable($campaign_id){	
	// 	$temp_table="campaign_$campaign_id";
	// 	// Drop table
	// 	$dropsql = "DROP TABLE IF EXISTS $temp_table";
	// 	$tablequery = Yii::app()->db->createCommand($dropsql)->execute();
	// 	// Recreate the table
	// 	$temp_sql="CREATE TABLE `".$temp_table."` (
	// 	`id` INT  AUTO_INCREMENT PRIMARY KEY ,
	// 	`station_name` varchar(200) not null,
	// 	`uniq_users` longtext  ,
	// 	`audience` float  ,
	// 	`slotid` INT  ,
	// 	`rate` float  ,
	// 	`universe` float  ,
	// 	`trp` float  ,
	// 	`start_time` time  ,
	// 	`end_time` time,
	// 	`DayWeek` varchar(200)
	// 	) ENGINE = MYISAM";
	// 	$tablequery = Yii::app()->db->createCommand($temp_sql)->execute();
	// 	return $temp_table;
	// }

	// public function GetCampaignAudienceData($demographicdata,$temp_table){
	// 	// $temp_table = $this->TempTable($brandid);
	// 	// reconstruct the data
	// 	$gender = $demographicdata->genderquery;
	// 	$regions = $demographicdata->topoquery;
	// 	$mediatype = $demographicdata->mediaquery;
	// 	$rural_urban = 'both';
	// 	$lsmrange = $demographicdata->lsmquery;
	// 	// $counties = $demographicdata['counties'];
	// 	$agelow = $demographicdata->startage;
	// 	$agehigh = $demographicdata->endage;
	// 	// Gender
	// 	if($gender=='Both'){
	// 		$genderquery = " ";
	// 	}else{
	// 		$genderquery = " AND user_profiles.gender ='$gender'";
	// 	}
	// 	// Topographies
	// 	if (!is_array($regions)) {
	// 		$regions = explode(',', $regions);
	// 	}
	// 	$regionsarray = array();
	// 	foreach ($regions as $keyvalue) {
	// 		$regionsarray[] = "'$keyvalue'";
	// 	}
	// 	$regionlist = implode(',', $regionsarray);
	// 	$regionquery = " AND user_profiles.Topographies IN ($regionlist) ";
	// 	// media type
	// 	$mediatype = explode(',', $mediatype);
	// 	$silly = array();
	// 	foreach ($mediatype as $kes) {
	// 		$silly[] = "'".$kes."'";
	// 	}
	// 	$mediatypelist = implode(',', $silly);
	// 	$mediaquery = " AND karfprocessed.mediatype IN ($mediatypelist) ";
	// 	// rural urban
	// 	if($rural_urban=='both'){
	// 		$ruralurbanquery = " ";
	// 	}else{
	// 		$ruralurbanquery = " AND user_profiles.SETTING ='$rural_urban'";
	// 	}
	// 	// lsm query
	// 	if(!is_array($lsmrange)){
	// 		$lsmrange = explode(',', $lsmrange);
	// 	}
	// 	$lsmarray = array();
	// 	foreach ($lsmrange as $keyvalue) {
	// 		$keyvalue = trim($keyvalue);
	// 		$lsmarray[] = "'LSM $keyvalue'";
	// 	}
	// 	$lsmlist = implode(', ', $lsmarray);
	// 	$lsmquery = " AND user_profiles.lsmx IN ($lsmlist) ";
	// 	$totaluniverse = 0;
	// 	// Get Your Universe based on Selected TOPOGRAPHIES
	// 	$universesql = "SELECT DISTINCT user_profiles.UserId, user_profiles.weight AS audience
	// 	FROM karfprocessed 
	// 	INNER JOIN user_profiles ON user_profiles.UserId=karfprocessed.UserId
	// 	INNER JOIN time_slots ON karfprocessed.start_time=time_slots.start_time
	// 	WHERE (user_profiles.ExactAGE BETWEEN $agelow AND $agehigh) AND
	// 	karfprocessed.start_time=time_slots.start_time AND
	// 	karfprocessed.end_time=time_slots.end_time AND 
	// 	karfprocessed.DayWeek=time_slots.DayWeek   
	// 	$mediaquery $regionquery $genderquery $ruralurbanquery $lsmquery";
	// 	$universedata = Yii::app()->karfdb->createCommand($universesql)->queryAll();
	// 	if($universedata){
	// 		foreach ($universedata as $weightvalues) {
	// 			$userweight = (float)$weightvalues['audience'];
	// 			$totaluniverse = $totaluniverse+$userweight;
	// 		}
	// 		$totaluniverse = (int)$totaluniverse;
	// 	}
	// 	// GET Data
	// 	$sql = "INSERT INTO postcampaign.$temp_table (station_name,uniq_users,audience,slotid,rate,universe,trp,start_time,end_time,DayWeek) 
	// 	SELECT karfprocessed.station AS station_name,
	// 	GROUP_CONCAT(DISTINCT user_profiles.UserId) AS uniq_users, 
	// 	ROUND(sum(DISTINCT user_profiles.weight)) AS audience,
	// 	time_slots.id AS slotid,
	// 	karfprocessed.rates AS rate,
	// 	$totaluniverse AS universe,
	// 	(ROUND(sum(DISTINCT user_profiles.weight))/$totaluniverse)*100 AS trp,
	// 	time_slots.start_time,
	// 	time_slots.end_time,
	// 	time_slots.DayWeek AS DayWeek
	// 	FROM karfprocessed 
	// 	INNER JOIN user_profiles ON user_profiles.UserId=karfprocessed.UserId
	// 	INNER JOIN time_slots ON karfprocessed.start_time=time_slots.start_time
	// 	WHERE (user_profiles.ExactAGE BETWEEN $agelow AND $agehigh) AND
	// 	karfprocessed.start_time=time_slots.start_time AND
	// 	karfprocessed.end_time=time_slots.end_time AND 
	// 	karfprocessed.DayWeek=time_slots.DayWeek 
	// 	$mediaquery $regionquery $genderquery $ruralurbanquery $lsmquery
	// 	GROUP BY karfprocessed.station,karfprocessed.DayWeek,karfprocessed.start_time";
	// 	$tablequery = Yii::app()->karfdb->createCommand($sql)->execute();
	// 	// return universe back
	// 	return $totaluniverse;
	// }

	public function GetCampaignSpots($campaign,$campaign_table,$model,$startdate,$enddate){
		$runid = $model->id;
		// Get the universe for trp calculations - etc
		$universe_sql = "SELECT universe FROM $campaign_table LIMIT 1";
		$uni_query = Yii::app()->db->createCommand($universe_sql)->queryRow();
		if($uni_query){
			$universe = $uni_query['universe'];
		}else{
			$universe = 0;
		}
		$campaignid = $campaign->id;
		// update karf names
		$updatekarfnames = $this->UpdateKarfNames($runid);
		$brandusers = array();
		$amount = 0;
		$spots = 0;
		$brandspots_sql = "SELECT * FROM brandspots WHERE runid = $runid";
		$brands_query = Yii::app()->db->createCommand($brandspots_sql)->queryAll();
		if($brands_query){
			foreach ($brands_query as $brandrow) {
				$udata = $this->UpdateSpotsData($brandrow,$campaignid,$universe);
				$brandusers[] = $udata['uniq_users'];
				$amount = $amount + $brandrow['rate'];
				$spots++;
			}
		}
		$grpanalysis = new CampaignCombinations;
		$grp = $grpanalysis->UpdateCombinations($runid,$universe,$brandusers);
		// obtain calculated values
		$reach = $grp['combinedreach'];
		$audience = $grp['totalaudience'];
		$frequency = $grp['combinedfrequency'];
		$ggrp = $reach * $frequency;
		$universe = $universe;
		$amount = $amount;
		$result_sql = "REPLACE INTO run_results (runid,spots,reach,audience,frequency,ggrp,universe,amount,startdate,enddate) 
		VALUES ($runid,$spots,'$reach','$audience','$frequency','$ggrp','$universe','$amount','$startdate','$enddate')";
		$rs_query = Yii::app()->db->createCommand($result_sql)->execute();
	}

	public function UpdateKarfNames($runid){
		$sql = "SELECT DISTINCT rf_name FROM brandspots WHERE runid=$runid";
		$rf_query = Yii::app()->db->createCommand($sql)->queryAll();
		if($rf_query){
			foreach ($rf_query as $rfrow) {
				$rfname = $rfrow['rf_name'];
				$karfname = $this->getKarfName($rfname);
				$updatesql = "UPDATE brandspots SET karf_name = '$karfname' WHERE rf_name= '$rfname' AND runid=$runid";
				$updateqry = Yii::app()->db->createCommand($updatesql)->execute();
			}
		}
	}

	public function UpdateSpotsData($spotdata,$campaignid,$universe){
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
			$slotid = $getudata['slotid'];
			$audience = $getudata['audience'];
			$uniq_users = $getudata['uniq_users'];
			$trp = ($audience / $universe) * 100;
		}else{
			$slotid = 0;
			$audience = 0;
			$uniq_users = '-';
			$trp = 0;
		}
		// update spots
		$updatesql = "UPDATE brandspots SET slotid=$slotid, uniq_users='$uniq_users',trp='$trp',audience='$audience',universe='$universe' WHERE id=$bspotid";
		$updatequery = Yii::app()->db->createCommand($updatesql)->execute();
		// package array
		return array('trp'=>$trp, 'audience'=>$audience, 'uniq_users'=>$uniq_users);
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

	public function getCampaignUniqueUsers($karfstation,$spot_time,$day_name,$campaignid){
		$temp_table="postcampaign.campaign_$campaignid";
		$sql = "SELECT $temp_table.station_name AS station_name, $temp_table.uniq_users, 
		$temp_table.start_time,
		$temp_table.end_time,$temp_table.DayWeek,$temp_table.audience,time_slots.id AS slotid
		FROM $temp_table 
		INNER JOIN time_slots ON $temp_table.start_time=time_slots.start_time
		WHERE $temp_table.station_name = '$karfstation' AND $temp_table.DayWeek='$day_name'
		AND $temp_table.end_time > '$spot_time'  AND $temp_table.start_time < '$spot_time'
		AND $temp_table.DayWeek = time_slots.DayWeek";
		$query = Yii::app()->karfdb->createCommand($sql)->queryRow();
		if ($query) {
			$afrow = $query;
			return $afrow;
		}else{
			return '-';
		}
	}
}