<?php
/**
* WeeklyMailCommand Command Class
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
class WeeklyMailCommand extends CConsoleCommand
{
	public function actionGenerate($argv){
		date_default_timezone_set('Africa/Nairobi');
		$id = $argv;
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
		
		$startdate = date('Y-m-d', strtotime('last week monday'));
		$enddate = date('Y-m-d', strtotime('last sunday'));

		/* Check The Type of Email Format */
		$sqlcheck="select transcription, classification, company_name, subs from company where transcription=1 and  company_id=$id limit 1";
		if($mailclassification = Company::model()->findBySql($sqlcheck)){
			$classification = $mailclassification->classification;
			$transcription = $mailclassification->transcription;
		}else{
			$classification = 0;
			$transcription = 0;
		}
		/* End Check of Email Format */

		/* 
		** Get The Content
		** Classified But Not Transcribed - Format Like Safaricom
		** Classified & Transcriped - Format Like Coca-cola
		*/
		if($classification==1 && $transcription==0){
			$transcript = '';
			$print = WeeklyMail::PrintStoriesClassified($id,$startdate,$enddate,$country,$currency);
			$industryprint = WeeklyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
		}elseif ($classification==1 && $transcription==1) {
			$transcript = WeeklyMail::Transcriptions($id,$startdate,$enddate,$country,$currency);
			$print = WeeklyMail::PrintStoriesClassifiedSummarised($id,$startdate,$enddate,$country,$currency);
			$industryprint = WeeklyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
		}else{
			$transcript = '';
			$print = WeeklyMail::PrintStories($id,$startdate,$enddate,$country,$currency);
			$industryprint = WeeklyMail::IndustryPrintStories($id,$startdate,$enddate,$country,$currency);
		}
		/* End Content */

		/* Get the Charts */
		$charts = MailCharts::GetCharts($id,$country);
		/* End Charts */
		
		$charts = MailCharts::GetCharts($id,$country);

		if($models = ClientUsers::model()->findAll('co_id=:a', array(':a'=>$id))){
			$count = 1;
			foreach ($models as $model) {
				$mail = WeeklyMail::ComposeMail($model,$print,$industryprint,$charts,$transcript);
				$subject = 'Weekly Mail';
				echo $count .' - '.$model->email.' | '.$model->UserName;
				echo "\n";
				// $email = $model->email;
				$email = 'steve.oyugi@reelforge.com';
				$name = $model->UserName;
				// if($compose = WeeklyMail::MailWrapper($name,$subject,$email,$mail)){
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
			if($compose = WeeklyMail::MailWrapper($name,$subject,$email,$mail)){
				echo "Mail Sent \n";
			}else{
				echo "Mail Not Sent \n";
			}
		}else{
			echo 'User Not Found ID:'.$id;
		}
	}
}