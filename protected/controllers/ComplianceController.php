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

class ComplianceController extends Controller
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
				'actions'=>array('index','process'),
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
		$model = new ComplianceSchedule;
		if(isset($_POST['ComplianceSchedule']))
			$model->attributes=$_POST['ComplianceSchedule'];
		if(isset($_POST['uploadcompliance']) && isset($_FILES['file'])){
			if(is_dir('docs/compliancefiles/')){
				$rand = rand();
				$filename = 'compliance_'.$rand.'_'.str_replace(' ', '_', $_FILES['file']['name']);
				$schedule_file = 'docs/compliancefiles/'.$filename;
				if(move_uploaded_file($_FILES['file']['tmp_name'],$schedule_file)){
					$model->compliance_name = $_POST['compliance_name'];
					$model->mediaschedule = $filename;
					$model->dateuploaded = date("Y-m-d H:i:s");
					$model->company_id = $_POST['company_id'];
					$model->brand_id = implode(',', $_POST['brand_id']);
					$model->country = 'KE';
					$model->admonth = $_POST['admonth'];
					$model->adyear = $_POST['adyear'];
					$model->save();
					// set flash
					Yii::app()->user->setFlash('success', "<strong>File uploaded! Continue to run! </strong>");
					$this->redirect(array('compliance/process/'.$model->id));
				}
			}else{
				Yii::app()->user->setFlash('danger', "<strong>Missing Upload Structure, notify Technical</strong>");
			}
		}else{
			// $model->compliance_name = "";
			$this->render('index', array('model'=>$model));
		}
	}

	public function actionProcess($id){
		$model = ComplianceSchedule::model()->find('id=:a', array(':a'=>$id));
		if($model){
			$schedule_id = $model->id;
			if(isset($_POST['submit_stations']) && isset($_POST['station_id'])){
				$rcstations = $_POST['station_id'];
				foreach ($rcstations as $key => $value) {
					$station_name = $key;
					$station_id = $value;
					$update_sql = "UPDATE compliance_period SET station_id = $station_id WHERE station_name='$station_name' AND schedule_id=$schedule_id";
					$update_qry = Yii::app()->compliancedb->createCommand($update_sql)->execute();
				}
				$this->redirect(array('compliance/process/'.$model->id));
			}

			if(isset($_POST['submit_adtypes']) && isset($_POST['entry_type_id'])){
				$rcadtypes = $_POST['entry_type_id'];
				foreach ($rcadtypes as $key => $value) {
					$adtype = $key;
					$entry_type_id = implode(',', $value);
					$update_sql = "UPDATE compliance_period SET entry_type_id = '$entry_type_id' WHERE adtype='$adtype' AND schedule_id=$schedule_id";
					$update_qry = Yii::app()->compliancedb->createCommand($update_sql)->execute();
				}
				$this->redirect(array('compliance/process/'.$model->id));
			}
			$this->render('process',array('model'=>$model));
		}else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
	}
}
