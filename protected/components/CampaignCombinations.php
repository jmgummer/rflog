<?php

/**
 * For planning db
 */
class CampaignCombinations
{
	// Get the session spots, contains rate, unique users, trp
	public function getSessionSpots($runid){
		$dynamicspots = array(); // This will hold mapped spots to their attributes e.g. uniq_users
		// Get all the spots for this session & get the uniq_users
		$sessionspots_sql = "SELECT DISTINCT id, uniq_users, rate AS cost, trp AS TRP FROM brandspots WHERE runid=$runid";
		$sessionspots_sqlquery = Yii::app()->karfdb->createCommand($sessionspots_sql)->queryAll();
		if ($sessionspots_sqlquery) {
			foreach ($sessionspots_sqlquery as $uservalue) {
				$id = $uservalue['id'];
				$cost = $uservalue['cost'];
				$userlist = $uservalue['uniq_users'];
				$TRP = $uservalue['TRP'];
				// Convert to An Array
				$dusers = explode(',', $userlist);
				$dynamicspots[] = array('id'=>$id,'userlist'=>$dusers,'cost'=>$cost,'TRP'=>$TRP);
			}
		}
		return $dynamicspots;
	}

	public function UpdateCombinations($runid,$universe,$mediauniverse,$allusers,$campaign_users_table){
		// Obtain All Mapped Users & Spots
		// Clean up the users
		$cleanuser_array = array();
		foreach ($allusers as $keyuser) {
			// create array
			$rec_array = explode(',', $keyuser);
			foreach ($rec_array as $actualvalue) {
				if(!in_array($actualvalue, $cleanuser_array) && $actualvalue!='-'){
					$cleanuser_array[] = $actualvalue;
				}
			}
		}
		// Re-assign back
		$allusers = $cleanuser_array;
		$allspots = $this->getSessionSpots($runid);
		// obtain the audience for reach
		$totalaudience = $this->DistinctUsersReach($allusers,$campaign_users_table);
		if($totalaudience!=0){
			// $combinedreach = ($totalaudience/$universe)*100;
			$combinedreach = ($totalaudience/$mediauniverse)*100;
		}else{
			$combinedreach = 0;
		}
		// Get the Total Cost of the Selected Spots & GRP in one go
		// GRP in this case is the sum of TRP's from the Uniq Spots
		/*
		** Add Reach & User List, Frequency
		** Compute Frequency Next
		** Find All Users - For Frequency you need to avoid finding unique users. It gives you the wrong value of frequency
		*/
		// the number of users
		$usercount = count($allusers);
		$totalfrequency = 0;
		foreach ($allusers as $userkey) {
			$frncy = 0;
			foreach ($allspots as $dukey) {
				$specific_spot_users = $dukey['userlist'];
				if(in_array($userkey, $specific_spot_users)){
					$frncy++;
				}
			}
			$totalfrequency = $totalfrequency + $frncy;
		}
		if($usercount==0 || $totalfrequency==0){
			$combinedfrequency = 0;
		}else{
			$combinedfrequency = ($totalfrequency/$usercount);
		}
		return array('combinedreach'=>$combinedreach,'combinedfrequency'=>$combinedfrequency,'totalaudience'=>$totalaudience);
	}

	public function UsersPerDayofWeek($campaign_table,$campaign_users_table){
		$dayaudiences = array();
		// get the potential unique audience for given weekday
		$dayweek_sql = "SELECT DISTINCT DayWeek FROM `$campaign_table`";
		// echo "$dayweek_sql <br>";
		$dayweek_qry = Yii::app()->karfdb->createCommand($dayweek_sql)->queryAll();
		if ($dayweek_qry) {
			foreach ($dayweek_qry as $weekvalue) {
				$DayWeek = $weekvalue['DayWeek'];
				$users_sql = "SELECT DISTINCT uniq_users FROM `$campaign_table` WHERE DayWeek='$DayWeek'";
				// echo "$users_sql <br>";
				$users_qry = Yii::app()->karfdb->createCommand($users_sql)->queryAll();
				if ($users_qry) {
					$userarray = array();
					foreach ($users_qry as $uservalue) {
						$userlist = $uservalue['uniq_users'];
						$dusers = explode(',', $userlist);
						foreach ($dusers as $keyvalue) {
							if(!in_array($keyvalue, $userarray)){
								$userarray[] = $keyvalue;
							}
						}
					}
					// pull in the total audience by getting the user weights
					$audience = $this->DistinctUsersReach($userarray,$campaign_users_table);
				}else{
					$audience = 0;
				}
				$dayaudiences[] = array('DayWeek'=>$DayWeek,'audience'=>$audience);
			}
		}
		return $dayaudiences;
		
	}

	public function DistinctUsersReach($distinctusers,$campaign_users_table){
		$totalaudience =0;
		$userarray = array();
		// // Get all the Users for this session & get the weights
		if(is_array($distinctusers) && count($distinctusers)>0){
			$userlist = implode(",", $distinctusers);
			$userlist = str_replace(",,", ",", $userlist);
			$userlist = str_replace("-,", "", $userlist);
			$userlist = rtrim($userlist,",");
			$distinctuweight = "SELECT DISTINCT UserId, audience AS weight FROM $campaign_users_table WHERE UserId IN ($userlist) GROUP BY UserId";
			$distinctquery = Yii::app()->karfdb->createCommand($distinctuweight)->queryAll();
			if($distinctquery){
				foreach ($distinctquery as $keyvalue) {
					$keyUserId = $keyvalue['UserId'];
					$keyUserWeight = $keyvalue['weight'];
					$totalaudience = $totalaudience + (int)$keyvalue['weight'];
				}
			}
		}
		return $totalaudience;
	}
}