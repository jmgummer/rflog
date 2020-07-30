<?php

/**
* HomeController Controller Class
* This Class Is Used To Handle all Home actions
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Reelmedia
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class PostcampaignController extends Controller
{
	/**
	 * @var This is the admin controller
	 */
	public $layout='//layouts/column1';

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','run','new','computerun','getdata','searchcompanies','custom'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	// dashboard page
	public function actionIndex()
	{
		$campaigns = new Campaigns('search');
        $campaigns->user_id = Yii::app()->user->user_id;
		$this->render('index', array('campaigns'=>$campaigns));
	}

	public function actionNew()
	{
		$this->render('new');
	}

	// dashboard page
	public function actionRun($id){
		$campaign = Campaigns::model()->find('id=:a', array(':a'=>$id));
		// check added brands that don't have runs
		$runs = new Common;
		$runchecks = $runs->CheckUnsetSegments($id);
		// get all available runs
		if(!$campaignrun = CampaignRuns::model()->find('campaign_id=:a', array(':a'=>$id))){
			$campaignrun = new CampaignRuns;
			$campaignrun->campaign_id = $id;
		}
		$this->render('run',array('model'=>$campaignrun,'campaign'=>$campaign));
	}

	public function actioncomputerun($id){
		if(isset($_GET['year'])){
			$adyear = $_GET['year'];
			$admonth = $_GET['month'];
		}else{
			$adyear = date("Y");
			$admonth = date("m");
		}
		$campaignrun = CampaignRuns::model()->find('id=:a', array(':a'=>$id));
		$campaign = Campaigns::model()->find('id=:a', array(':a'=>$campaignrun->campaign_id));
		$this->render('computerun',array('model'=>$campaignrun,'campaign'=>$campaign,'adyear'=>$adyear,'admonth'=>$admonth));
	}

	public function actionGetdata(){
		$user_id = Yii::app()->user->user_id;
		$start_date = date('Y-m-d', strtotime('first day of last month'));
		$end_date = date('Y-m-d', strtotime('last day of last month'));
		/* Combinations */
		if(isset($_POST['getresults'])){
			$plantitle = $_POST['plantitle'];
			$gender = $_POST['gender'];
			$regions = $_POST['regions'];
			$agelow = $_POST['agelow'];
			$agehigh = $_POST['agehigh'];
			// repackage problematic mediaforms
			$mf_array = array();
			if(isset($_POST['mediaforms'])){
				$mf_array[] = $_POST['mediaforms'];
			}
			$mediaforms = $mf_array;
			$rural_urban = $_POST['rural_urban'];
			$lsmrange = $_POST['lsmrange'];
			if($_POST['ransessionnumber']){
				$session_id = $_POST['ransessionnumber'];
			}
			// create campaign
			$campaign_name = $_POST['plantitle'];
			if(!$campaign = Campaigns::model()->find('campaign_name=:a AND user_id=:b', array(':a'=>$campaign_name,':b'=>$user_id))){
				$campaign = new Campaigns;
				$campaign->campaign_name = $campaign_name;
				$campaign->user_id = $user_id;
				$campaign->save();
			}
			// campaign id
			$campaign_id = $campaign->id;
			// save demographic data
			if(!$demographicdata = CampaignDemographic::model()->find('campaign_id=:a', array(':a'=>$campaign_id))){
				$demographicdata = new CampaignDemographic;				
				$demographicdata->campaign_id = $campaign_id;
				$demographicdata->genderquery = $_POST['gender'];
				$demographicdata->startage = $_POST['agelow'];
				$demographicdata->endage = $_POST['agehigh'];
				$demographicdata->rural_urban = $_POST['rural_urban'];
				$demographicdata->topoquery = implode(',', $_POST['regions']);
				$mf_list = implode(',', $mf_array);
				$demographicdata->mediaquery = $mf_list;
				$demographicdata->lsmquery = trim(str_replace('LSM', '', implode(', ', $_POST['lsmrange'])));
				$demographicdata->save();
			}
			// create the campaign table
			$campaign_table = PostCampaign::CampaignTempTable($campaign_id);
			// add the required spots
			$gdata = PostCampaign::GetDataGuided($plantitle,$gender,$regions,$agelow,$agehigh,$rural_urban,$lsmrange,$mediaforms,$session_id,$campaign_table,$start_date,$end_date);
			if($gdata==true){
				echo $campaign_id;
			}else{
				echo false;
			}
		}
	}

	public function actionCustom(){
		$this->render('custom');
	}

	public function actionSearchcompanies(){
		if(isset($_POST['search_text']) && !empty($_POST['search_text']) && $_POST['search_text']!=''){
			$search_text = $_POST['search_text'];
			$sql = "SELECT company_id, company_name FROM user_table WHERE company_name LIKE '%$search_text%'";
			if($companies = Yii::app()->forgedb->createCommand($sql)->queryAll()){
				echo "<option>-Select-</option>";
				foreach ($companies as $value) {
					$company_id=$value["company_id"];
					$company_name=trim($value["company_name"]);
					echo '<option value="'.$company_id.'">'.$company_name.'</option>';
				}
			}else{
				echo '<option>No Results Found</option>';
			}
		}
		if(isset($_POST['getactivebrands']) && !empty($_POST['company_id']) && $_POST['company_id']!=''){
			$company_id = $_POST['company_id'];
			if(isset($_POST['adyear']) && !empty($_POST['adyear']) && $_POST['adyear']!='' ){
				$adyear = $_POST['adyear'];
				$admonth = $_POST['admonth'];
				$extrasql = " AND adyear=$adyear AND admonth=$admonth";
			}else{
				$extrasql = " ";
			}
			$sql = "SELECT DISTINCT brand_id, brand_name FROM brand_tracker WHERE brand_tracker.company_id = '$company_id' $extrasql ORDER BY brand_name";
			if($companies = Yii::app()->forgedb->createCommand($sql)->queryAll()){
				echo "<option>-Select-</option>";
				foreach ($companies as $value) {
					$brand_id=$value["brand_id"];
					$brand_name=trim($value["brand_name"]);
					echo '<option value="'.$brand_id.'">'.$brand_name.'</option>';
				}
			}else{
				echo '<option>No Results Found</option>';
			}
		}
		if(isset($_POST['getbrandsegments']) && !empty($_POST['brand_id']) && $_POST['brand_id']!=''){
			$runs = new Common;
			$brand_id = $_POST['brand_id'];
			$sql = "SELECT * FROM segment_brands WHERE brand_id = $brand_id";
			if($segments = Yii::app()->karfdb->createCommand($sql)->queryAll()){
				foreach ($segments as $value) {
					$campaign_id = $value['campaign_id'];
					$runchecks = $runs->CheckUnsetSegments($campaign_id);
				}
			
			}
			if(isset($_POST['adyear']) && !empty($_POST['adyear']) && $_POST['adyear']!='' ){
				$adyear = $_POST['adyear'];
				$admonth = $_POST['admonth'];
				$extra_url = "/$adyear/$admonth";
			}else{
				$extra_url = " ";
			}
			$sql = "SELECT campaign_runs.id, campaign_runs.run_name, campaigns.campaign_name 
			FROM campaign_runs 
			INNER JOIN campaigns ON campaigns.id=campaign_runs.campaign_id
			WHERE brand_id = $brand_id";
			if($campaign_runs = Yii::app()->karfdb->createCommand($sql)->queryAll()){
				echo "<table class='table table-bordered table-condensed' >";
				echo "<thead><th>Brand Name</th><th>Sub Segment</th><th>Actions</th> </thead>";
				foreach ($campaign_runs as $value) {
					$run_id = $value['id'];
					$run_name = $value['run_name'];
					$segment_name = $value['campaign_name'];
					$actions = "";

					$edit_link = Yii::app()->createUrl('postcampaign/computerun/'.$run_id.$extra_url);
					$link = "<a href='$edit_link' class=''>Compute</a>";

					echo "<tr>";
					echo "<td>$run_name</td>";
					echo "<td>$segment_name</td>";
					echo "<td>$link</td>";
					echo "</tr>";
				}
				echo "</table>";
			}else{
				echo "<p><strong>No Segments / Sub Segments Found</strong></p>";
				echo "<p><a href='#'>Assign to a Sub Segments</a></p>";
			}
		}
	}
}
