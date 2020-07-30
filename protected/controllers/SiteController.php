<?php

/**
* SiteController Controller Class
* This Class Is Used To Handle all Site actions
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

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->isGuest){
			$this->redirect(array('site/login'));
		}else{
			$this->redirect(array('home/index'));
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout='//layouts/error';
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin(){
		$this->layout='//layouts/login';
		$model=new LoginForm;
		if(Yii::app()->user->isGuest){
			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			$return = Yii::app()->user->returnUrl;
			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
				{
					$this->redirect(array('home/index'));
				}
			}
			if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
				$model->username = $_POST['username'];
				$model->password = $_POST['password'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
				{
					$this->redirect(array('home/index'));
				}
			}
			$this->render('login',array('model'=>$model));
		}else{
			$this->redirect(Yii::app()->user->returnUrl);
		}
	}

	public function actionSuper()
	{
		$model=new LoginForm;
		if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
			$model->username = $_POST['username'];
			$model->password = $_POST['password'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				echo 'success';
			}else{
				echo 'utter failure!';
			}
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		if(isset(Yii::app()->user->reelmedia_session_id)){
			$userid = Yii::app()->user->user_id;
			$session_id = Yii::app()->user->reelmedia_session_id;
			$sql = "SELECT * FROM reelmedia_sessions WHERE user_id=$userid and session_id ='$session_id' ";
			if($inactive = ReelmediaSessions::model()->findBySql($sql)){
				$inactive->active = 0;
				$inactive->end_time = date('H:i:s');
				$inactive->save();
			}
		}
		Yii::app()->user->logout();
		if(isset($_SESSION)){
			session_start();
			session_destroy();
		}
		$this->redirect('login');
	}
}