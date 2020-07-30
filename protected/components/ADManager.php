<?php

class ADManager{

    public $date_array;
    public $station_array;
    public $schedule_blocks;

    public function Handler($schedule_id,$brandid,$admonth,$adyear){
        $this->ads = $this->AnvilAds($brandid,$admonth,$adyear);
        $this->GetStations($schedule_id);
        $this->GetDates($schedule_id);
        $this->adblocks = $this->GetScheduleAdblocks($schedule_id);
        $this->schedules = $this->RebuildSchedules($schedule_id);
    }

    public function GetStations($schedule_id){
        $sql = "SELECT DISTINCT station_name,station_id FROM compliance_period WHERE schedule_id = $schedule_id ORDER BY station_name";
        $stations = CompliancePeriod::model()->findAllBySql($sql);
        foreach ($stations as $stationvalue) {
            $this->station_array[] = array('station_id'=>$stationvalue->station_id,'station_name'=>$stationvalue->station_name);
        }
    }

    public function GetDates($schedule_id){
        $sql = "SELECT DISTINCT addate FROM compliance_period WHERE schedule_id = $schedule_id ORDER BY addate";
        $dates = CompliancePeriod::model()->findAllBySql($sql);
        foreach ($dates as $datevalue) {
            $this->date_array[] = array('addate'=>$datevalue->addate);
        }
    }

    public function GetStationAdTypes($schedule_id,$stationid){
        $typearray = array();
        $sql = "SELECT DISTINCT entry_type_id, adtype FROM compliance_period WHERE schedule_id = $schedule_id AND station_id=$stationid ORDER BY adtype";
        $adtypes = CompliancePeriod::model()->findAllBySql($sql);
        foreach ($adtypes as $adtype_value) {
            $typearray[] = array('entry_type_id'=>$adtype_value->entry_type_id,'adtype'=>$adtype_value->adtype);
        }
        return $typearray;
    }

    public function GetScheduleAdblocks($schedule_id){
        $timeblock_array = array();
        $sql = "SELECT station_name, station_id, adtype, entry_type_id, timeblock 
        FROM compliance_period 
        WHERE schedule_id = $schedule_id GROUP BY station_name,adtype,timeblock";
        $adblocks = CompliancePeriod::model()->findAllBySql($sql);
        foreach ($adblocks as $adblock_value) {
            $timeblock_array[] = array('station_name'=>$adblock_value->station_name,'station_id'=>$adblock_value->station_id,'adtype'=>$adblock_value->adtype,'entry_type_id'=>$adblock_value->entry_type_id,'timeblock'=>trim($adblock_value->timeblock) );
        }
        return $timeblock_array;
    }

    public function RebuildSchedules($schedule_id){
        $adblocks = $this->adblocks;
        $tablecontent = "";
        $tablecontent .= "<table class='table table-condensed table-bordered' style='font-size: 10px;'>";
        $tablecontent .= "<tr><td><strong>Station</strong></td><td><strong>Ad Type</strong></td><td><strong>Time Block</strong></td>";
        $compliance_stations = $this->station_array;
        if(is_array($this->date_array) && count($this->date_array)>0){
            $compliancedates = $this->date_array;
            foreach ($compliancedates as $keydate) {
                $tdate = date("d", strtotime($keydate['addate']));
                $tablecontent .= "<td><strong>$tdate</strong></td>";
            }
            $tablecontent .= "<td><strong>Total</strong></total>";
            $tablecontent .= "<td><strong>Compliance</strong></total>";
            $tablecontent .= "</tr>";
            // next
            foreach ($adblocks as $adblock_value) {
                $stationid = $adblock_value['station_id'];
                $station_name = $adblock_value['station_name'];
                $adtype = $adblock_value['adtype'];
                $timeblock = $adblock_value['timeblock'];
                $entry_type_id = $adblock_value['entry_type_id'];
                $startend_time = explode('-', trim($timeblock));
                if(count($startend_time)==2){
                    if(strtotime($startend_time[0])> strtotime('00:10') ){
                        $run_starttime = date("H:i:s",strtotime($startend_time[0])-299);
                    }else{
                        $run_starttime = date("H:i:s",strtotime($startend_time[0]));
                    }
                    if(strtotime($startend_time[1]) < strtotime('23:50')){
                        $run_endtime = date("H:i:s",strtotime($startend_time[1])+299);
                    }else{
                        $run_endtime = date("H:i:s",strtotime($startend_time[1]));
                    }
                }else{
                    if(strtotime($startend_time[0])> strtotime('00:10') && strtotime($startend_time[0])< strtotime('23:15') ){
                        $run_starttime = date("H:i:s",strtotime($startend_time[0])-299);
                        $run_endtime = date("H:i:s",strtotime($startend_time[0])+299);
                    }elseif (strtotime($startend_time[0])> strtotime('23:15')) {
                        $run_starttime = date("H:i:s",strtotime($startend_time[0])-1800);
                        $run_endtime = date("H:i:s",strtotime($startend_time[0])+1800);
                    }else{
                        $run_starttime = date("H:i:s",strtotime($startend_time[0]));
                        $run_endtime = date("H:i:s",strtotime($startend_time[0])+1800);
                    }
                }       
                $tablecontent .= "<tr>";
                $tablecontent .= "<td>$station_name</td>";
                $tablecontent .= "<td>$adtype</td>";
                $tablecontent .= "<td>$timeblock</td>";
                $totalexpected = 0;
                $totalactual = 0;
                foreach ($compliancedates as $keydate) {
                    $addate = $keydate['addate'];
                    $expectedruns = $this->ExpectedRuns($schedule_id,$addate,$stationid,$entry_type_id,$timeblock);
                    $totalexpected = $expectedruns + $totalexpected;
                    $block_adcount = $this->AdsInBlock($addate,$stationid,$entry_type_id,$run_starttime,$run_endtime);
                    $totalactual = $block_adcount + $totalactual;
                    // nothing is expected & nothing ran
                    if($expectedruns==0 && $block_adcount==0){
                        $tablecontent .= "<td bgcolor='#FFFFFF'>$block_adcount</td>";
                    }
                    // nothing is expected but something ran
                    if($expectedruns==0 && $block_adcount!=0){
                        $tablecontent .= "<td bgcolor='#f2b72b'>$block_adcount</td>";
                    }
                    // something is expected but what ran is less than expected
                    if($expectedruns!=0 && $block_adcount<$expectedruns){
                        $tablecontent .= "<td bgcolor='#FF0000'>$block_adcount/$expectedruns</td>";
                    }
                    // something is expected and what ran is same as expected
                    if($expectedruns!=0 && $block_adcount==$expectedruns){
                        $tablecontent .= "<td bgcolor='#00FF00'>$block_adcount/$expectedruns</td>";
                    }
                    // something is expected and what ran is more than expected
                    if($expectedruns!=0 && $block_adcount>$expectedruns){
                        $tablecontent .= "<td bgcolor='#800080' style='color:#FFF;'>$block_adcount/$expectedruns</td>";
                    }
                }
                // nothing is expected & nothing ran
                if($totalexpected==0 && $totalactual==0){
                    $tablecontent .= "<td bgcolor='#FFFFFF'>$totalactual</td>";
                }
                // nothing is expected but something ran
                if($totalexpected==0 && $totalactual!=0){
                    $tablecontent .= "<td bgcolor='#f2b72b'>$totalactual</td>";
                }
                // something is expected but what ran is less than expected
                if($totalexpected!=0 && $totalactual<$totalexpected){
                    $tablecontent .= "<td bgcolor='#FF0000'>$totalactual/$totalexpected</td>";
                }
                // something is expected and what ran is same as expected
                if($totalexpected!=0 && $totalactual==$totalexpected){
                    $tablecontent .= "<td bgcolor='#00FF00'>$totalactual/$totalexpected</td>";
                }
                // something is expected and what ran is more than expected
                if($totalexpected!=0 && $totalactual>$totalexpected){
                    $tablecontent .= "<td bgcolor='#800080' style='color:#FFF;'>$totalactual/$totalexpected</td>";
                }
                if($totalexpected==0){
                    $compliancepec = 'N/A ';
                }else{
                    $compliancepec = round(($totalactual/$totalexpected)*100);
                }
                $tablecontent .= "<td><strong>$compliancepec%</strong></td>";
                $tablecontent .= "</tr>";
            }
        }else{
            $tablecontent .= "</thead>";
        }
        $tablecontent .= "</table>";
        $app_pdf = AppPDF::StandardPDF($tablecontent); 
        echo $app_pdf;
        echo $tablecontent;
        // return $tablecontent;
    }

    public function ExpectedRuns($schedule_id,$addate,$stationid,$entry_type_id,$timeblock){
        $sql = "SELECT expected_ads FROM compliance_period 
        WHERE schedule_id = $schedule_id AND addate='$addate' AND station_id=$stationid AND entry_type_id IN ($entry_type_id) AND timeblock='$timeblock' ";
        $expectedruns = CompliancePeriod::model()->findBySql($sql);
        if($expectedruns){
            return $expectedruns->expected_ads;
        }else{
            return 0;
        }
    }

    public function AdsInBlock($addate,$stationid,$entry_type_id,$run_starttime,$run_endtime){
        $temptable = $this->ads;
        $sql = "SELECT COUNT(adid) AS ads FROM $temptable 
        WHERE ad_date='$addate' AND station_id=$stationid AND entry_type_id IN ($entry_type_id) 
        AND (ad_time BETWEEN '$run_starttime' AND '$run_endtime')";
        $adsquery = Yii::app()->forgedb->createCommand($sql)->queryRow();
        if($adsquery){
            return $adsquery['ads'];
        }else{
            return 0;
        }
    }

    // Get all ads
    public function AnvilAds($brand_id,$admonth,$adyear){
        if(strlen($admonth)<2){
            $admonth = "0".$admonth;
        }
        $brandidlist = str_replace(',', '', $brand_id);
        $temptable = "compliane_temp$admonth$adyear".rand();
        $sample_month="reelforge_sample_"  .$adyear."_".$admonth;
        $mention_month="djmentions_"  .$adyear."_".$admonth;
        // sql statement
        $sql = "CREATE TEMPORARY TABLE $temptable AS
        SELECT $sample_month.reel_auto_id AS adid, 
        $sample_month.reel_date AS ad_date,
        $sample_month.reel_time AS ad_time,
        station.station_name AS station_name, 
        station.station_id AS station_id, 
        djmentions_entry_types.entry_type  AS entry_type, 
        djmentions_entry_types.entry_type_id AS entry_type_id,
        brand_table.brand_name
        FROM $sample_month 
        INNER JOIN station ON station.station_id = $sample_month.station_id
        INNER JOIN brand_table ON brand_table.brand_id=$sample_month.brand_id
        INNER JOIN djmentions_entry_types ON djmentions_entry_types.auto_id = $sample_month.entry_type_id
        WHERE $sample_month.brand_id IN ($brand_id) AND $sample_month.active=1 
        UNION
        SELECT $mention_month.auto_id AS adid, 
        $mention_month.`date` AS ad_date,
        $mention_month.`time` AS ad_time,
        station.station_name AS station_name, 
        station.station_id AS station_id, 
        djmentions_entry_types.entry_type  AS entry_type, 
        djmentions_entry_types.entry_type_id AS entry_type_id,
        brand_table.brand_name
        FROM $mention_month 
        INNER JOIN station ON station.station_id = $mention_month.station_id
        INNER JOIN brand_table ON brand_table.brand_id=$mention_month.brand_id
        INNER JOIN djmentions_entry_types ON djmentions_entry_types.auto_id = $mention_month.entry_type_id
        WHERE $mention_month.brand_id IN ($brand_id) AND $mention_month.active=1";
        $insertqry = Yii::app()->forgedb->createCommand($sql)->execute();
        return $temptable;
    }

}