<?php

/**
* AccountController Controller Class
* It is used to manage the account actions, such as user update, passwords
* data can identity the user.
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
*
* @package     Reelmedia
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/


class AccountController  extends Controller
{
	/**
	 * @var This is the Archive controller
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
				'actions'=>array('index','password','users','updateuser','companies','assignclients','resetpassword'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$model=$this->loadModel(Yii::app()->user->user_id);
		if(Yii::app()->user->usertype=='agency'){
			if(isset($_POST['AgencyUsers']))
			{
				$model->attributes=$_POST['AgencyUsers'];
				if($model->save()){
					Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated");
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
				}
			}
		}else{
			if(isset($_POST['ClientUsers']))
			{
				$model->attributes=$_POST['ClientUsers'];
				if($model->save()){
					Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated");
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
				}
			}
		}
		
		$this->render('index',array('model'=>$model,));
	}

	public function actionPassword()
	{
		$model=$this->loadModel(Yii::app()->user->user_id);
		if(Yii::app()->user->usertype=='agency'){
			if(isset($_POST['AgencyUsers'])){
				$old = md5($_POST['AgencyUsers']['dummypass']);
				$new = md5($_POST['AgencyUsers']['dummypass2']);
				$confirm = md5($_POST['AgencyUsers']['dummypass3']);

				if($_POST['AgencyUsers']['dummypass2'] =='' || $_POST['AgencyUsers']['dummypass3']==''){
					Yii::app()->user->setFlash('danger', "<strong>Error ! You need to add values in the Password Fields! </strong>");
				}else{
					if($old==$model->password && $new==$confirm){
						$model->password=$confirm;
						if($model->save()){
							Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
						}
					}else{
						Yii::app()->user->setFlash('danger', "<strong>Error ! Your account could not be updated, check your passwords again! </strong>");
					}
				}
			}
		}else{
			if(isset($_POST['ClientUsers'])){
				$old = md5($_POST['ClientUsers']['dummypass']);
				$new = md5($_POST['ClientUsers']['dummypass2']);
				$confirm = md5($_POST['ClientUsers']['dummypass3']);

				if($_POST['ClientUsers']['dummypass2'] =='' || $_POST['ClientUsers']['dummypass3']==''){
					Yii::app()->user->setFlash('danger', "<strong>Error ! You need to add values in the Password Fields! </strong>");
				}else{
					if($old==$model->password && $new==$confirm){
						$model->password=$confirm;
						if($model->save()){
							Yii::app()->user->setFlash('success', "<strong>Success ! Your account password has been updated, login again to effect changes! </strong>");
						}
					}else{
						Yii::app()->user->setFlash('danger', "<strong>Error ! Your account could not be updated, check your passwords again! </strong>");
					}
				}
			}
		}
		$this->render('update',array('model'=>$model,));
	}

	public function loadModel($id)
	{
		if(Yii::app()->user->usertype=='agency'){
			$agency = AgencyUsers::model()->find('agency_users_id=:a', array(':a'=>$id));
		}else{
			$model = ClientUsers::model()->find('client_users_id=:a', array(':a'=>$id));
		}
		
		if($model===null && $agency===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if($model===null && $agency!=null){
			$model=$agency;
		}	
		return $model;
	}

	public function actionUsers()
	{
		$this->render('users');
	}

	public function actionAdduser()
	{
		$this->render('adduser');
	}

	public function actionUpdateuser($id)
	{
		$model = AgencyUsers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		else
			if(isset($_POST['AgencyUsers'])){
				$model->attributes=$_POST['AgencyUsers'];
				if($model->save()){
					Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Details Updated");
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error ! </strong>Your Details were not Updated, please try later");
				}
			}
			$this->render('updateuser', array('model'=>$model));
		
		
	}

	public function actionCompanies()
	{
		$this->render('companies');
	}

	public function actionAssignclients($id)
	{
		$model = AgencyUsers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		else
			/* Remove Company Assignment */
			if(isset($_POST['remove'])){
				if(isset($_POST['company_id'])){
					$company_id = Yii::app()->input->stripClean($_POST['company_id']);
					foreach($company_id as $set_id)
					{
						$values = AgencyUserClient::model()->find('company_id=:a and agency_users_id=:b',array(':a'=>$set_id,':b'=>$id));
						if($values==true){
							if($values->delete()){
								Yii::app()->user->setFlash('success', "<strong>Success !</strong> Company(s) Removed ");
							}else{
								Yii::app()->user->setFlash('danger', "<strong>Warning !</strong> Some Company(s) could NOT be Removed ");
							}
						}else{
							Yii::app()->user->setFlash('danger', "<strong>Warning !</strong> Company(s) could not be found, try again later ");
						}
					}
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error !</strong> You need to select at LEAST one Company");
				}
			}

			/* Add Company Assignment */

			if(isset($_POST['assign'])){
				if(isset($_POST['company_id'])){
					$company_id = Yii::app()->input->stripClean($_POST['company_id']);
					foreach($company_id as $set_id)
					{
						$values = AgencyUserClient::model()->find('company_id=:a and agency_users_id=:b',array(':a'=>$set_id,':b'=>$id));
						if($values!=true){
							$values = new AgencyUserClient;
							$values->agency_users_id = $id;
							$values->company_id = $set_id;
							$values->reelmedia_email = 0;
							$values->reelonline_email = 0;
							if($values->save()){
								Yii::app()->user->setFlash('success', "<strong>Success !</strong> Company(s) Assigned ");
							}else{
								Yii::app()->user->setFlash('danger', "<strong>Error !</strong> Some Company(s) could not be Assigned ");
							}
						}else{
							Yii::app()->user->setFlash('danger', "<strong>Warning !</strong> The user is already assigned to theCompany(s)");
						}
					}
				}else{
					Yii::app()->user->setFlash('danger', "<strong>Error !</strong> You need to select at LEAST one Company");
				}
			}
			$user_id = $id;
			$this ->render('assignclients',array('user_id'=>$user_id));
	}

	public function actionResetpassword($id)
	{
		$reset = GeneratePassword::ResetPassword($id);
		Yii::app()->user->setFlash('success', "<strong>$reset</strong>");
		$this->redirect(array('account/users'));
	}
	
}
?>