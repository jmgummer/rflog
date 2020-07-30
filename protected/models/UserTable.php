<?php

/**
 * This is the model class for table "user_table".
 *
 * The followings are the available columns in table 'user_table':
 * @property integer $company_id
 * @property string $company_name
 * @property string $company_rep_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $last_auto_password
 * @property string $description
 * @property string $registerDate
 * @property string $lastvisitDate
 * @property string $activation
 * @property integer $level
 * @property integer $is_client
 * @property string $usertype
 * @property integer $master_id
 * @property string $login
 * @property string $trp
 * @property string $picture
 * @property string $show_rate
 * @property string $pofemail
 * @property integer $rpts_only
 * @property integer $agency_id
 * @property integer $user_status
 * @property integer $plus_status
 * @property integer $keysubscription
 * @property integer $competitor_alert
 * @property integer $competitor_print
 * @property integer $competitor_electronic
 * @property integer $competitor_activity
 * @property string $report_start_date
 * @property string $report_end_date
 */
class UserTable extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, trp, picture, agency_id, keysubscription', 'required'),
			array('level, is_client, master_id, rpts_only, agency_id, user_status, plus_status, keysubscription, competitor_alert, competitor_print, competitor_electronic, competitor_activity', 'numerical', 'integerOnly'=>true),
			array('company_name, company_rep_name, username, email, password, description, picture', 'length', 'max'=>100),
			array('last_auto_password', 'length', 'max'=>30),
			array('activation, usertype, login, trp, show_rate, pofemail', 'length', 'max'=>1),
			array('registerDate, lastvisitDate, report_start_date, report_end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('company_id, company_name, company_rep_name, username, email, password, last_auto_password, description, registerDate, lastvisitDate, activation, level, is_client, usertype, master_id, login, trp, picture, show_rate, pofemail, rpts_only, agency_id, user_status, plus_status, keysubscription, competitor_alert, competitor_print, competitor_electronic, competitor_activity, report_start_date, report_end_date', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'company_rep_name' => 'Company Rep Name',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'last_auto_password' => 'Last Auto Password',
			'description' => 'Description',
			'registerDate' => 'Register Date',
			'lastvisitDate' => 'Lastvisit Date',
			'activation' => 'Activation',
			'level' => 'Level',
			'is_client' => 'Is Client',
			'usertype' => 'Usertype',
			'master_id' => 'Master',
			'login' => 'Login',
			'trp' => 'Trp',
			'picture' => 'Picture',
			'show_rate' => 'Show Rate',
			'pofemail' => 'Pofemail',
			'rpts_only' => 'Rpts Only',
			'agency_id' => 'Agency',
			'user_status' => 'User Status',
			'plus_status' => 'Plus Status',
			'keysubscription' => 'Keysubscription',
			'competitor_alert' => 'Competitor Alert',
			'competitor_print' => 'Competitor Print',
			'competitor_electronic' => 'Competitor Electronic',
			'competitor_activity' => 'Competitor Activity',
			'report_start_date' => 'Report Start Date',
			'report_end_date' => 'Report End Date',
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

		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_rep_name',$this->company_rep_name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_auto_password',$this->last_auto_password,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('registerDate',$this->registerDate,true);
		$criteria->compare('lastvisitDate',$this->lastvisitDate,true);
		$criteria->compare('activation',$this->activation,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('is_client',$this->is_client);
		$criteria->compare('usertype',$this->usertype,true);
		$criteria->compare('master_id',$this->master_id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('trp',$this->trp,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('show_rate',$this->show_rate,true);
		$criteria->compare('pofemail',$this->pofemail,true);
		$criteria->compare('rpts_only',$this->rpts_only);
		$criteria->compare('agency_id',$this->agency_id);
		$criteria->compare('user_status',$this->user_status);
		$criteria->compare('plus_status',$this->plus_status);
		$criteria->compare('keysubscription',$this->keysubscription);
		$criteria->compare('competitor_alert',$this->competitor_alert);
		$criteria->compare('competitor_print',$this->competitor_print);
		$criteria->compare('competitor_electronic',$this->competitor_electronic);
		$criteria->compare('competitor_activity',$this->competitor_activity);
		$criteria->compare('report_start_date',$this->report_start_date,true);
		$criteria->compare('report_end_date',$this->report_end_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->forgedb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserTable the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
