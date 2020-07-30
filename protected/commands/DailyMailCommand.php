<?php
date_default_timezone_set("Africa/Nairobi");
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
/**
* DailyMailCommand Command Class
* This Class Is Used To Handle all Site actions
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

class DailyMailCommand extends CConsoleCommand
{
	/** 
	** Create a Shell Script to Be Run
	** Create the file if it does not exist 
	*/
	public function actionClientMailCron(){
		$script_body="#!/bin/sh \n\n";
		$filename_script='/home/srv/www/htdocs/reelmediad_v2/scripts/dailymail_client_script.sh';
		$sql_company="select distinct company.company_id,mail_schedule, company_name  from company,industry_company,company_country where
		industry_company.company_id=company.company_id and Client=1 and company_country.company_id = company.company_id and 
		country_id !=2  order by mail_schedule , priority desc ";
		if($models = Company::model()->findAllBySql($sql_company)){
			foreach ($models as $model) {
				$company_id =$model->company_id;
				$mail_schedule=substr($model->mail_schedule,0,2) + 0 ;
				$script_body.="/usr/bin/php /home/srv/www/htdocs/reelmediad_v2/protected/yiic dailymail generate --argv=$company_id --schedule_time=$mail_schedule  &  \n";
			}
		}
		if (!file_exists($filename_script)) {
			echo "The file $filename_script does not exist";
			echo "\n\n";
			echo $cmd = "touch $filename_script";
			echo "\n\n";
			exec($cmd);
			$cmd="chmod 755 $filename_script";
			echo "\n\n";
			exec($cmd);
		}
		if (!$handle = fopen($filename_script, 'w')) {
			echo "Cannot open file ($filename_script)";
			exit;
		}else{
			echo "File Opened";
			echo "\n\n";
		}
		if (fwrite($handle, $script_body) === FALSE) {
			echo "Cannot write to file ($filename_script)";
			exit;
		}else{
			echo "File Written\n\n";
		}
		
		fclose($handle);
		$cmd="chmod 755 $filename_script";
		exec($cmd);
	}
	
	/** 
	** Check time, we can send this before time.
	** If Emails Are Held Dont Send, Stop
	** Check The Type of Email Format
	** Get The Content for All Transcribed, Classified & None Classified or Transcribed
	** Classified But Not Transcribed - Format Like Safaricom
	** Classified & Transcriped - Format Like Coca-cola
	*/

	public function actionGenerate($argv,$schedule_time){
		date_default_timezone_set('Africa/Nairobi');
		echo "Scheduled Time - ";
		echo $schedule_time."\n";
		echo "Company ID - ";
		echo $id = $argv;
		echo "\n";
		echo "Date - ";
		echo $today=date("Y-m-d");
		echo "\n";
		echo "Time - ";
		echo $time=date("H:i:s");
		echo "\n";
		$myTime=date('H');
		echo $myTime ." " . $schedule_time . " \n";
		if($myTime<$schedule_time ){ 
			echo "Too early \n"; 
			exit(); 
		}
		$sql="select status, sent from dailymail_hold where mail_date='$today' and schedule_time=$schedule_time ";
		$checkheld=DailymailHold::model()->findBySql($sql);
		if($checkheld==true && $checkheld->status==1){
			echo "Emails Held \n";
			exit();
		}else{
			echo "Emails Not Held \n";
			$sql_check="select * from dailymail where mail_date='$today'  and company_id=$id";
			$query_check=DailymailCheck::model()->findBySql($sql_check);
			// Switch Back to True in Production
			if($query_check==false){
				// echo "Email Already Sent Out To This Client \n\n";
				echo "Email Not Sent Out, so we can't update :( \n";
				exit();
			}else{
				if($query_check->mailplus==1){
					echo "Email Already Sent Out, so we have to LEAVE :) \n";
					exit(); 
				}else{
					if($id!=1){
						echo "Company Not Safaricom, For Tests, EXIT PLEASE :( \n";
						exit(); 
					}
					$company_country = 'select * from company_country where company_id ='.$id;
					if($company_country = CompanyCountry::model()->findBySql($company_country)){
						$country = $company_country->country_id;
					}else{
						$country = 1;
					}
					if($currency = Country::model()->find('country_id=:a', array(':a'=>$country))){
						$currency = $currency->currency;
					}else{
						$currency = 'KSH';
					}
					$startdate = $enddate = date('Y-m-d');
					$electronic_startdate = $electronic_enddate = date('Y-m-d', strtotime(' -1 day'));

					$sqlcheck="select transcription, classification, company_name, subs from company where company_id=$id limit 1";
					if($mailclassification = Company::model()->findBySql($sqlcheck)){
						$classification = $mailclassification->classification;
						$transcription = $mailclassification->transcription;
					}else{
						$classification = 0;
						$transcription = 0;
					}
					
					if($classification==1 && $transcription==0){
						echo "level1 \n";
						$transcript = '';
						$print = DailyMail::PrintStoriesClassified($id,$startdate,$enddate,$country,$currency);
						$electronic = DailyMail::ElectronicStoriesClassified($id,$electronic_startdate,$electronic_enddate,$country,$currency);
						$industryprint = DailyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
						$industryelectronic = DailyMail::IndustryElectronicStories($id,$electronic_startdate,$electronic_enddate,$country,$currency);
					}elseif ($classification==1 && $transcription==1) {
						echo "level2 \n";
						$transcript = DailyMail::Transcriptions($id,$startdate,$enddate,$country,$currency);
						$print = DailyMail::PrintStoriesClassifiedSummarised($id,$startdate,$enddate,$country,$currency);
						$electronic = DailyMail::ElectronicStoriesClassifiedSummarised($id,$electronic_startdate,$electronic_enddate,$country,$currency);
						$industryprint = DailyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
						$industryelectronic = DailyMail::IndustryElectronicStories($id,$electronic_startdate,$electronic_enddate,$country,$currency);
					}else{
						echo "level3 \n";
						$transcript = '';
						$print = DailyMail::PrintStories($id,$startdate,$enddate,$country,$currency);
						$electronic = DailyMail::ElectronicStories($id,$electronic_startdate,$electronic_enddate,$country,$currency);
						$industryprint = DailyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
						$industryelectronic = DailyMail::IndustryElectronicStories($id,$electronic_startdate,$electronic_enddate,$country,$currency);
					}

					$charts = MailCharts::GetCharts($id,$country);
					// $charts = array();

					if($models = ClientUsers::model()->findAll('co_id=:a AND reelmedia=1', array(':a'=>$id))){
						echo "Users Found \n";
						$count = 1;
						foreach ($models as $model) {
							$mail = DailyMail::ComposeMail($model,$print,$electronic,$industryprint,$industryelectronic,$charts,$transcript);
							$subjectdate = date('d-m-Y h:i:s');
							$subject = 'Daily Mail - Reelmedia Review: '.$subjectdate;
							echo $count .' - '.$model->email.' | '.$model->UserName;
							echo "\n";
							
							$email = 'steve.oyugi@reelforge.com';
							$name = $model->UserName;
							// $email = $model->email;
							// if($compose = DailyMail::MailWrapper($name,$subject,$email,$mail)){
							// 	echo "Mail Sent \n";
							// }else{
							// 	echo "Mail Not Sent \n";
							// }
							$count++;
						}
						/* 
						** Use for Test 
						** Fetch last details and post Email
						*/
						if($compose = DailyMail::MailWrapper($name,$subject,$email,$mail)){
							echo "Mail Sent \n";
						}else{
							echo "Mail Not Sent \n";
						}
					}else{
						echo 'User Not Found ID:'.$id;
					}
					$query_check->mailplus=1;
					$query_check->save();

				}
				
				// $sql_update="insert into  dailymail(sent,status,mail_time,schedule_time,mail_date,company_id) values(1,1,'$today $time','$schedule_time','$today',$id)";
    //             $insertsql = Yii::app()->db2->createCommand($sql_update)->execute();
			}
		}

		
		
	}
}