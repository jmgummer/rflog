<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $password
 * @property string $email
 * @property integer $usertype
 * @property integer $status
 * @property string $dateadded
 * @property string $lastlogin
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'platform_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, firstname, lastname, password, email, usertype, status', 'required'),
			array('usertype, status, client_type', 'numerical', 'integerOnly'=>true),
			array('username, firstname, lastname', 'length', 'max'=>80),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, firstname, lastname, password, email, usertype, status, dateadded, lastlogin, client_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'firstname' => 'First Name',
			'lastname' => 'Last Name',
			'password' => 'Password',
			'email' => 'Email',
			'usertype' => 'Usertype',
			'status' => 'Status',
			'dateadded' => 'Date Added',
			'lastlogin' => 'Last Login',
			'client_type'=>'Client Type'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('usertype',$this->usertype);
		$criteria->compare('status',$this->status);
		$criteria->compare('client_type',$this->client_type);
		$criteria->compare('dateadded',$this->dateadded,true);
		$criteria->compare('lastlogin',$this->lastlogin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getFullName(){
		return $this->firstname.' '.$this->lastname;
	}

	// public function getActions(){
	// 	$edit_link = Yii::app()->createUrl('admin/client/'.$this->id);
	// 	$link = "<a href='$edit_link' class='btn btn-xs btn-info'>Details</a>";
	// 	echo  $link;
	// }

	public function getDateAdded(){
		return date("d-m-Y", strtotime($this->dateadded));
	}

	public function getClientType(){
		if($this->client_type==1){
			return 'NGO';
		}elseif ($this->client_type==2) {
			return 'Media House';
		}else{
			return 'Individual';
		}
	}

	public function getActions(){
		if($this->id!=2 && $this->id!=4 && $this->id!=7){
			$rtlink = "<a href='#' class='btn btn-xs btn-warning' onclick='event.preventDefault();PopUserUpdate($this->id);'>Edit</a>
			<a href='#' class='btn btn-xs btn-danger' onclick='event.preventDefault();PopPasswordUpdate($this->id);'>Reset Password</a>";
			echo  $rtlink;
		}
		
	}
}
