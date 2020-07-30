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

class HomeController extends Controller
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
				'actions'=>array('index','manual','auto','upload', 'searchcompanies', 'process', 'viewplan','download','getdata','logdata'),
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
		$model = new Djmentions;
		$this->render('index', array('models'=>$model));
	}

	public function actionUpload(){
		$reconupload = new ReconFileUploads;
		if(isset($_POST) && isset($_FILES['file'])){
			if(is_dir('docs/reconfiles/')){
				$rand = rand();
				$filename = 'recon_txlogs_'.$rand.'_'.str_replace(' ', '_', $_FILES['file']['name']);
				$pof_file = 'docs/reconfiles/'.$filename;
				if(move_uploaded_file($_FILES['file']['tmp_name'],$pof_file)){
					$reconupload->filename = $filename;
					$reconupload->upload_date = date("Y-m-d H:i:s");
					$reconupload->logtype = $_POST['logtype'];
					$reconupload->station_id = $_POST['station_id'];
					$reconupload->save();
					// set flash
					Yii::app()->user->setFlash('success', "<strong>File uploaded! Continue to run! </strong>");
					$this->redirect(array('home/upload/'));
				}
			}
        } 
		$this->render('upload',array('model'=>$reconupload));
	}

	public function actionProcess($id){
		$reconupload = ReconFileUploads::model()->find('id=:a', array(':a'=>$id));
		if($reconupload){
			$this->render('process',array('model'=>$reconupload));
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
	}

	public function actionManual()
	{
		$model = new Djmentions;
		Yii::import('ext.multimodel.MultiModelForm');
    	$validatedMembers = array();
    	$insertaddittions = 0;
		if(isset($_POST['brand_id'])){
			if(isset($_POST['Djmentions'])){
				$ad_brand_id = $_POST['brand_id'];
				$ad_station_id = $_POST['station_id'];
				// get the number of elements to loop through, take away 1 element because you start COUNT at ZERO
				$arrayelements = count($_POST['Djmentions']['time'])-1;
				$arraycount = 0;
				while ($arraycount <= $arrayelements) {
					$ad_date = trim($_POST['Djmentions']['date'][$arraycount]);
					// multiple time uploads
					$ad_time_array = explode(',', $_POST['Djmentions']['time'][$arraycount]);
					// multiple duration uploads
					$ad_duration_array = explode(',', $_POST['Djmentions']['duration'][$arraycount]);
					$ad_entry_type_id = $_POST['Djmentions']['entry_type_id'][$arraycount];
					// get the entry type name
					$sql = "SELECT entry_type FROM djmentions_entry_types WHERE entry_type_id = $ad_entry_type_id";
					$entryqry = Yii::app()->forgedb->createCommand($sql)->queryRow();
					$ad_entry_type = $entryqry['entry_type'];
					// continue
					$ad_Program = $_POST['Djmentions']['Program'][$arraycount];
					$timearray = 0;
					foreach ($ad_time_array as $time_value) {
						$ad_time = trim($time_value);
						if(count($ad_duration_array)>0 && isset($ad_duration_array[$timearray])){
							$ad_duration = (int)trim($ad_duration_array[$timearray]);
						}else{
							$ad_duration = (int)trim($_POST['Djmentions']['duration'][$arraycount]);
						}
						$insertsql = "INSERT IGNORE INTO djmentions (`brand_id`,`station_id`,`date`,`time`,`duration`,`entry_type`, `entry_type_id`,`Program`,`comment`,`adtype`) VALUES ($ad_brand_id,$ad_station_id,'$ad_date','$ad_time','$ad_duration','$ad_entry_type','$ad_entry_type_id','$ad_Program','OK',3);";
						Yii::app()->forgedb->createCommand($insertsql)->execute();
						$insertaddittions++;
						$timearray++;
					}
					$arraycount++;
				}
			}
		}
		$this->render('manual', array('model'=>$model,'complianceItems'=>$model,'validatedMembers' => $validatedMembers,'insertaddittions'=>$insertaddittions));
	}

	public function actionAuto()
	{
		$model = new ReelforgeSample;
		Yii::import('ext.multimodel.MultiModelForm');
    	$validatedMembers = array();
    	$insertaddittions = 0;
		if(isset($_POST['brand_id'])){
			if(isset($_POST['ReelforgeSample'])){
				$ad_brand_id = $_POST['brand_id'];
				$ad_station_id = $_POST['station_id'];
				$ad_incantation_id = $_POST['incantation_id'];
				// get the incantation name
				$sql = "SELECT incantation.incantation_name FROM incantation WHERE incantation.incantation_id=$ad_incantation_id";
				$entryqry = Yii::app()->forgedb->createCommand($sql)->queryRow();
				$incantation_name = $entryqry['incantation_name'];
				// continue
				$ad_entry_type_id = $_POST['entry_type_id'];
				// get the number of elements to loop through, take away 1 element because you start COUNT at ZERO
				$arrayelements = count($_POST['ReelforgeSample']['reel_time'])-1;
				$arraycount = 0;
				while ($arraycount <= $arrayelements) {
					$ad_date = trim($_POST['ReelforgeSample']['reel_date'][$arraycount]);
					// multiple time uploads
					$ad_time_array = explode(',', $_POST['ReelforgeSample']['reel_time'][$arraycount]);
					$timearray = 0;
					foreach ($ad_time_array as $time_value) {
						$ad_time = trim($time_value);
						$insertsql = "INSERT IGNORE INTO `reelforge_sample` (`brand_id`, `station_id`, `reel_date`, `reel_time`, `incantation_id`,`reel_incarntation`,`comment`, `entry_type_id`, `active`,`adtype`) VALUES ($ad_brand_id, $ad_station_id, '$ad_date', '$ad_time', $ad_incantation_id,\"$incantation_name\",'OK', '$ad_entry_type_id', 1,3);";
						Yii::app()->forgedb->createCommand($insertsql)->execute();
						$insertaddittions++;
						$timearray++;
					}
					$arraycount++;
				}
			}
		}
		$this->render('auto', array('model'=>$model,'complianceItems'=>$model,'validatedMembers' => $validatedMembers,'insertaddittions'=>$insertaddittions));
	}

	public function actionGetdata()
	{
		if(isset($_POST['getads']) && isset($_POST['brand_id']) &&  isset($_POST['station_id']) && isset($_POST['entry_type_id']) ){
			$incantations_ordered = array();
			$station_id = $_POST['station_id'];
			$incantation_id = $_POST['entry_type_id'];
			$brand_id = $_POST['brand_id'];
			$sql = "SELECT REPLACE(REPLACE(REPLACE(LOWER(station_name), 'fm', '') , 'tv', ''),'news','') AS station_name , station_code, station_type, 
			LOWER(`Language`) AS station_language FROM station WHERE station_id =$station_id LIMIT 1";
			if($stations = Yii::app()->forgedb->createCommand($sql)->queryRow()){
				$station_name = trim($stations['station_name']);
				$station_code = trim($stations['station_code']);
				$station_type_qry = trim($stations['station_type']);
				$station_language = trim($stations['station_language']);
				$excludeids = array();

				//get station mpg
				if($station_type_qry=='Radio' || $station_type_qry=='radio'){
					$mpg = 0;
					$tvc_ex = " AND (incantation_name NOT LIKE '%tvc%' AND incantation_name NOT LIKE '%\_tv%' AND incantation_name NOT LIKE '% tv %')";
				}else{
					$mpg = 1;
					$tvc_ex = "";
				}
				//      return incantation from station being analyzed
				$namesql = "SELECT incantation_id, incantation_name FROM incantation
				WHERE incantation_brand_id = $brand_id
				AND incantation.active=1
				AND incantation.mpg=$mpg
				AND incantation.incantation_entry_type_id=$incantation_id $tvc_ex
				AND (incantation.incantation_name LIKE '%$station_name%' OR incantation.incantation_file LIKE '$station_code\_%' 
				OR incantation.incantation_file LIKE 'm\_$station_code\_%')
				ORDER BY incantation_id desc";
				if($name_ordered = Yii::app()->forgedb->createCommand($namesql)->queryAll()){
					foreach ($name_ordered as $in_row) {
						if(!in_array($in_row['incantation_id'], $excludeids)){
							$excludeids[] = $in_row['incantation_id'];
						}
						$incantations_ordered[] = $in_row;
					}
				}
				// exclude found entries
				if(!empty($excludeids)){
					$imploded = implode(',',$excludeids);
					$exclude_string = " AND incantation_id NOT IN ($imploded)";
				}else{
					$exclude_string = " ";
				}
				// return incantation from language being analyzed
				if($station_language!=null || $station_language!=''){
					$namesql = "SELECT incantation_id, incantation_name FROM incantation
					WHERE incantation_brand_id = $brand_id
					AND incantation.active=1
					AND incantation.mpg=$mpg
					AND incantation.incantation_entry_type_id=$incantation_id $exclude_string $tvc_ex
					AND (incantation.incantation_name LIKE '%$station_language%')
					ORDER BY incantation_id desc";
					if($name_ordered = Yii::app()->forgedb->createCommand($namesql)->queryAll()){
						foreach ($name_ordered as $in_row) {
							if(!in_array($in_row['incantation_id'], $excludeids)){
								$excludeids[] = $in_row['incantation_id'];
							}
							$incantations_ordered[] = $in_row;
						}
					}
				}
				// exclude found entries
				if(!empty($excludeids)){
					$imploded = implode(',',$excludeids);
					$exclude_string = " AND incantation_id NOT IN ($imploded)";
				}else{
					$exclude_string = " ";
				}
				// run for others
				$namesql = "SELECT incantation_id, incantation_name FROM incantation
				WHERE incantation_brand_id = $brand_id
				AND incantation.active=1
				AND incantation.mpg=$mpg
				AND incantation.incantation_entry_type_id=$incantation_id $exclude_string $tvc_ex ORDER BY incantation_id desc";
				if($name_ordered = Yii::app()->forgedb->createCommand($namesql)->queryAll()){
					foreach ($name_ordered as $in_row) {
						$incantations_ordered[] = $in_row;
					}
				}
				if(count($incantations_ordered)>0){
					foreach ($incantations_ordered as $value) {
						$incantation_id = $value["incantation_id"];
						$incantation_name = trim($value["incantation_name"]);
						echo '<option value="'.$incantation_id.'">'.$incantation_name.'</option>';
					}
				}else{
					echo "<option>-No Incantations Found-</option>";
				}
			}
		}
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
			$sql = "SELECT DISTINCT brand_id, brand_name FROM brand_table WHERE company_id = '$company_id' ORDER BY brand_name";
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
	}

	public function actionLogdata(){
		if(isset($_POST['getadtypes']) && isset($_POST['lg_rf_company_id']) && isset($_POST['recon_file']) ){
			$recon_file = $_POST['recon_file'];
			$rf_company_id = $_POST['lg_rf_company_id'];
			// sql statement
			$sql = "SELECT DISTINCT djmentions_entry_types.entry_type_id, djmentions_entry_types.entry_type 
			FROM djmentions_entry_types 
			INNER JOIN recon_temp ON recon_temp.rf_entry_type_id=djmentions_entry_types.entry_type_id
			WHERE recon_file = $recon_file AND rf_company_id = $rf_company_id
			GROUP BY recon_temp.rf_entry_type_id";
			// execute
			$data = DjmentionsEntryTypes::model()->findAllBySql($sql);
			$data = CHtml::listData($data,'entry_type_id','entry_type');
			echo "<option>Select Ad Type</option>";
			foreach($data as $value=>$name){
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
			}
		}

		if(isset($_POST['getlogbrands']) && isset($_POST['rf_entry_type_id']) && isset($_POST['recon_file']) && isset($_POST['lg_rf_company_id'])){
			$recon_file = $_POST['recon_file'];
			$rf_company_id = $_POST['lg_rf_company_id'];
			$rf_entry_type_id = $_POST['rf_entry_type_id'];
			$sql = "SELECT DISTINCT recon_temp.log_sub_brand_name 
			FROM recon_temp 
			WHERE recon_file=$recon_file AND rf_company_id = $rf_company_id AND rf_entry_type_id=$rf_entry_type_id";
			$sub_brands = ReconTemp::model()->findAllBySql($sql);
			$data = CHtml::listData($sub_brands,'log_sub_brand_name','log_sub_brand_name');
			echo "<option>Select Log Brand</option>";
			foreach($data as $value=>$name){
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
			}
		}

		if(isset($_POST['getlogcampaigns']) && isset($_POST['rf_entry_type_id']) && isset($_POST['recon_file']) && isset($_POST['lg_rf_company_id']) ){
			$recon_file = $_POST['recon_file'];
			$rf_company_id = $_POST['lg_rf_company_id'];
			$rf_entry_type_id = $_POST['rf_entry_type_id'];
			$sub_brand_name = $_POST['log_sub_brand_name'];
			$sql = "SELECT DISTINCT recon_temp.log_campaign_name 
			FROM recon_temp 
			WHERE recon_file=$recon_file AND rf_company_id = $rf_company_id AND rf_entry_type_id=$rf_entry_type_id 
			AND log_sub_brand_name='$sub_brand_name' AND rf_brand_id IS NULL ";
			$log_campaigns = ReconTemp::model()->findAllBySql($sql);
			$data = CHtml::listData($log_campaigns,'log_campaign_name','log_campaign_name');
			echo "<option>Select Campaign</option>";
			foreach($data as $value=>$name){
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
			}
		}

// 		log_sub_brand_name
// lg_rf_entry_type_id
// lg_rf_company_id

// recon_file


	}
}
