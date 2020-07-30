<?php

/**
* Common Component Class
* This Class Is Used To Handle Regular/Common Tasks
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Reelmedia
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class Common{

	/**
	*
	* @return  Return A clean integer values
	* @throws  InvalidArgumentException
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function ExcelNumberFormat($value){
		$formatted = $value;
		$cleaned = str_replace(",", "", $formatted);
		return $cleaned;
	}

    public static function CheckRA_APIKEY($api_key){
        $sql = "SELECT company.company_name, company_mapping.company_id, company_mapping.country_id, countries.country_code, 
        company_mapping.pr_company_id, company_mapping.ad_company_id, company_mapping.ooh_company_id, company_mapping.online_company_id  
        FROM company_mapping 
        INNER JOIN company ON company.id = company_mapping.company_id
        INNER JOIN company_subscription ON company_subscription.company_id=company_mapping.company_id
        INNER JOIN countries ON countries.id=company_mapping.country_id
        WHERE company_subscription.subscription_status=1 AND company.api_key='$api_key'";
        $companyquery = Yii::app()->radb->createCommand($sql)->queryAll();
        // build array
        if($companyquery){
            $c_array = array();
            foreach ($companyquery as $row) {
                $companyuser = strtolower(trim(str_replace(' ', '_', $row['company_name'])));
                $password = sha1($companyuser);
                $ra_clientid = $row['company_id'];
                $c_array[] = array(
                    'cid'=>$row['company_id'],
                    'company_name'=>$row['company_name'],
                    'ccd'=>$row['country_code'],
                    'prid'=>$row['pr_company_id'],
                    'adid'=>$row['ad_company_id'],
                    'ooid'=>$row['ooh_company_id'],
                    'onid'=>$row['online_company_id'],
                    'companyuser'=>$companyuser
                );
            }
            $dateadded = date("Y-m-d H:i:s");
            // add the last user to the platform user
            $insert_sql = "INSERT IGNORE INTO platform_users (username,firstname,lastname,password,usertype,status,dateadded,ra_clientid,ra_apikey) 
            VALUES('$companyuser','$companyuser','-','$password',2,1,'$dateadded',$ra_clientid,'$api_key')";
            $rs_query = Yii::app()->db->createCommand($insert_sql)->execute();
            // add mapping
            $select_user = "SELECT id AS user_id FROM platform_users WHERE username = '$companyuser'";
            $user_query = Yii::app()->db->createCommand($select_user)->queryRow();
            if($user_query){
                $user_id = $user_query['user_id'];
                foreach ($c_array as $keyvalue) {
                    $country = $keyvalue['ccd'];
                    $company_id = $keyvalue['adid'];
                    // $user_id,$company_id,'$country'
                    $insert_sql = "INSERT IGNORE INTO company_mapping (user_id,company_id,country) VALUES ($user_id,$company_id,'$country')";
                    $cm_query = Yii::app()->db->createCommand($insert_sql)->execute();
                }
                // force create a new session
                $model = new LoginForm;
                $authenticator = new UserIdentity($companyuser,$password);
                $client = Users::model()->find('username=:a AND status=1', array(':a'=>$companyuser));

                $model->username = $companyuser;
                $model->password = $companyuser;
                // validate user input and redirect to the previous page if valid
                if($model->validate() && $model->login())
                {
                    // redirect(array('postcampaign/custom'));
                    return true;
                }else{
                    // echo "Invalid user";
                    return false;
                }

                // if($client!=FALSE){
                //     echo "found";

                //     $authenticator->username = 'admin';
                //     $authenticator->setState('user_id', $client->id);
                //     $authenticator->setState('client_name',$client->username);
                //     $authenticator->setState('client_type',$client->client_type);
                //     $authenticator->setState('usertype',$client->usertype);
                //     $authenticator->setState('FullName',$client->FullName);
                //     $client->lastlogin = date('Y-m-d H:i:s');
                //     $client->save();
                // }else{
                //     echo "not found";
                // }
            }
            // return $c_array;
        }else{
            return FALSE;
        }
    }

    public static function CreateUserSession($companydata){

    }

	public function CheckUnsetSegments($campaign_id){
		$sql = "SELECT DISTINCT segment_brands.brand_name AS run_name, segment_brands.brand_id AS brand_id FROM segment_brands WHERE campaign_id=$campaign_id";
		$query = Yii::app()->karfdb->createCommand($sql)->queryAll();
		if ($query) {
			foreach ($query as $uservalue) {
				$run_name = addslashes($uservalue['run_name']);
				$brand_id = $uservalue['brand_id'];
				$insert_sql = "INSERT IGNORE INTO campaign_runs (run_name,campaign_id,run_status,run_type,brand_id) VALUES('$run_name','$campaign_id',0,1,'$brand_id')";
				$insert_query = Yii::app()->karfdb->createCommand($insert_sql)->execute();
			}
		}
	}

	public function TempTable($tablename){
        // create statement
        $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS `$tablename` 
        (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `adid` int(11) NOT NULL,
            `flight_date_time` DATETIME NOT NULL,
            `ad_date` DATE NOT NULL,
            `ad_time` TIME NOT NULL,
            `company_name` VARCHAR(100) NOT NULL,
            `brand_name` VARCHAR(100) NOT NULL,
            `station_name` VARCHAR(100) NOT NULL,
            `station_id` INT NOT NULL,
            `platform` VARCHAR(100) NOT NULL,
            `entry_type` VARCHAR(100) NOT NULL,
            `entry_type_id` INT NOT NULL,
            `industry_name` VARCHAR(100) NOT NULL,
            `sub_industry_name` VARCHAR(100) NOT NULL,
            `rate` FLOAT NOT NULL,
            `duration` FLOAT NOT NULL,
            `url` TEXT NOT NULL,
            `adtable` VARCHAR(5),
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_row` (adid,ad_date,ad_time,company_name,brand_name,station_name,entry_type)
        )";
        $createquery = Yii::app()->forgedb->createCommand($sql)->execute();
        return $tablename;
    }

    public function RemovePrevious($brand_name,$runid,$start_date,$end_date){
        $sql = "DELETE FROM brandspots WHERE runid = $runid AND brand_name='$brand_name'";
        $adsquery = Yii::app()->karfdb->createCommand($sql)->execute();
    }

	public function AnvilAdsByCID($brand_id,$start_date,$end_date,$runid,$station_type){
        // create company archive table
        $company_name = "customer_$brand_id$brand_id";
        // brand_name
        $name_sql = "SELECT brand_name FROM brand_table WHERE brand_id=$brand_id LIMIT 1";
        $name_query = Yii::app()->forgedb->createCommand($name_sql)->queryRow();
        if($name_query){
            $brand_name = $name_query['brand_name'];
            // delete old records
            $this->RemovePrevious($brand_name,$runid,$start_date,$end_date);
        }else{
            $brand_name = "Unknown Brand";
        }
        /* Date Formating Starts Here */
        $year_start     = date('Y',strtotime($start_date));  
        $month_start    = date('m',strtotime($start_date));  
        $day_start      = date('d',strtotime($start_date));
        $year_end       = date('Y',strtotime($end_date)); 
        $month_end      = date('m',strtotime($end_date)); 
        $day_end        = date('d',strtotime($end_date));
        if($station_type=='all'){
        	$station_type_sql = "";
        }else{
            $exploded = explode(',', $station_type);
            $silly = array();
            foreach ($exploded as $kes) {
                $silly[] = "'".$kes."'";
            }
            $mediatypelist = implode(',', $silly);
        	$station_type_sql = " AND station.station_type IN ($mediatypelist)";
        }
        // create archive month table
        $monthname = "ad_tracker_".str_replace(' ', '_', strtolower($company_name)).'_'.rand();
        $companytable= $this->CreateMonthlyArchive($monthname);
        // run loops
        for ($x=$year_start;$x<=$year_end;$x++){
            if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
            if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}
            $month_start_count=$month_start_count+0;
            for ($y=$month_start_count;$y<=$month_end_count;$y++){
                if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
                // create archive month table
                $tablename = "advertising_".$x."_".$my_month."_".$brand_id;
                $temptable = $this->TempTable($tablename);
                // data sub tables
                $sample_month="reelforge_sample_"  .$x."_".$my_month;
                $mention_month="djmentions_"  .$x."_".$my_month;
                // run a union combining autos & manuals
                $sql = "INSERT IGNORE INTO $temptable (adid,ad_date,ad_time,brand_name,station_name,station_id,platform,entry_type,entry_type_id,rate,duration,adtable)
                SELECT $sample_month.reel_auto_id AS adid, 
                $sample_month.reel_date AS ad_date,
                $sample_month.reel_time AS ad_time,
                brand_table.brand_name AS brand_name, 
                station.station_name AS station_name, 
                station.station_id AS station_id, 
                station.station_type AS platform,
                djmentions_entry_types.entry_type  AS entry_type, 
                djmentions_entry_types.entry_type_id AS entry_type_id,
                $sample_month.rate, incantation.incantation_length AS duration, 
                'SR' AS adtable
                FROM $sample_month 
                INNER JOIN station ON station.station_id = $sample_month.station_id
                INNER JOIN brand_table ON brand_table.brand_id=$sample_month.brand_id
                INNER JOIN djmentions_entry_types ON djmentions_entry_types.auto_id = $sample_month.entry_type_id
                INNER JOIN incantation ON incantation.incantation_id = $sample_month.incantation_id
                WHERE brand_table.brand_id=$brand_id AND 
                ($sample_month.reel_date BETWEEN '$start_date' AND '$end_date') AND $sample_month.active=1 $station_type_sql
                UNION
                SELECT $mention_month.auto_id AS adid, 
                $mention_month.`date` AS ad_date,
                $mention_month.`time` AS ad_time,
                brand_table.brand_name AS brand_name, 
                station.station_name AS station_name, 
                station.station_id AS station_id, 
                station.station_type AS platform,
                djmentions_entry_types.entry_type  AS entry_type, 
                djmentions_entry_types.entry_type_id AS entry_type_id,
                $mention_month.rate, $mention_month.duration AS duration, 
                'JD' AS adtable
                FROM $mention_month 
                INNER JOIN station ON station.station_id = $mention_month.station_id
                INNER JOIN brand_table ON brand_table.brand_id=$mention_month.brand_id
                INNER JOIN djmentions_entry_types ON djmentions_entry_types.auto_id = $mention_month.entry_type_id
                WHERE brand_table.brand_id=$brand_id AND 
                ($mention_month.`date` BETWEEN '$start_date' AND '$end_date') AND $mention_month.active=1 $station_type_sql 
                ORDER BY ad_date, ad_time";
                $adsquery = Yii::app()->forgedb->createCommand($sql)->execute();
                $this->UpdateSync($temptable,$runid);
            }
        }
    }

    public function UpdateSync($temptable,$runid){
        $tempselect = "SELECT * FROM $temptable";
        $mentionsquery = Yii::app()->forgedb->createCommand($tempselect)->queryAll();
        if($mentionsquery){
            $sqlinserts = array();
            foreach ($mentionsquery as $row) {
            	$adid 		= $row['adid'];
            	$spot_date 		= $row['ad_date'];
                $spot_time 		= $row['ad_time'];
                $brand_name 	= $row['brand_name'];
                $rf_name 		= $row['station_name'] ;
				$spot_type 		= $row['entry_type'];
                $entry_type_id  = $row['entry_type_id'];
				$rate 			= $row['rate'];
				$spot_duration 	= $row['duration'];
				$day_name 		= date("l",strtotime(trim($row['ad_date'])));
				$runid 			= $runid;
				// package the array for bulk insert
                $sqlinserts[] = "('$adid','$spot_date','$spot_time','$brand_name','$rf_name','$spot_type','$entry_type_id','$rate','$spot_duration','$day_name','$runid')";
                if(count($sqlinserts)>999){
                    $bulkinsert = $this->BulkInsert($sqlinserts);
                    unset($sqlinserts);
                    $sqlinserts = array();
                }
            }
            if(count($sqlinserts)>0){
                $bulkinsert = $this->BulkInsert($sqlinserts);
                unset($sqlinserts);
                $sqlinserts = array();
            }
        }
    }

    public function BulkInsert($sqlinserts){
        $multidump = "INSERT IGNORE INTO `brandspots` (adid,spot_date,spot_time,brand_name,rf_name,spot_type,entry_type_id,rate,spot_duration,day_name,runid) VALUES ".implode(',', $sqlinserts);
		$adsquery = Yii::app()->karfdb->createCommand($multidump)->execute();
    }

    public function CreateMonthlyArchive($monthname){
        // create table if doesn't exists
        $createsql = "CREATE TEMPORARY TABLE IF NOT EXISTS `$monthname` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `adid` INT(11) NOT NULL,
            `ad_date` DATE NOT NULL,
            `ad_time` TIME NOT NULL,
            `company_name` VARCHAR(100) NOT NULL,
            `brand_name` VARCHAR(100) NOT NULL,
            `station_name` VARCHAR(100) NOT NULL,
            `platform` VARCHAR(100) NOT NULL,
            `entry_type` VARCHAR(100) NOT NULL,
            `rate` FLOAT NOT NULL,
            `duration` FLOAT NOT NULL,
            `url` TEXT NOT NULL,
            `admonth` INT(11) NOT NULL,
            `adyear` INT(11) NOT NULL,
            `rfguid` VARCHAR(200) NOT NULL,
            `station_id` INT(11) NOT NULL,
            `entry_type_id` INT(11) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `uniq_row` (`adid`, `ad_date`, `ad_time`, `brand_name`, `station_name`, `entry_type`)
        )";
        $createquery = Yii::app()->forgedb->createCommand($createsql)->execute();
        return $monthname;
    }
}