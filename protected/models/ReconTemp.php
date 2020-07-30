<?php

/**
 * This is the model class for table "recon_temp".
 *
 * The followings are the available columns in table 'recon_temp':
 * @property string $id
 * @property integer $recon_file
 * @property string $log_campaign_name
 * @property string $log_station_name
 * @property string $log_date
 * @property string $log_time
 * @property string $rf_company_id
 * @property string $log_company_name
 * @property string $rf_brand_id
 * @property string $log_brand_name
 * @property string $log_sub_brand_name
 * @property string $rf_incantation_id
 * @property string $rf_station_id
 * @property string $adtype_name
 * @property integer $rf_entry_type_id
 * @property integer $ad_duration
 */
class ReconTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recon_temp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('recon_file, rf_entry_type_id, ad_duration', 'numerical', 'integerOnly'=>true),
			array('log_campaign_name', 'length', 'max'=>200),
			array('log_station_name, log_company_name, log_brand_name, log_sub_brand_name, adtype_name', 'length', 'max'=>100),
			array('rf_company_id, rf_brand_id, rf_incantation_id, rf_station_id', 'length', 'max'=>6),
			array('log_date, log_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, recon_file, log_campaign_name, log_station_name, log_date, log_time, rf_company_id, log_company_name, rf_brand_id, log_brand_name, log_sub_brand_name, rf_incantation_id, rf_station_id, adtype_name, rf_entry_type_id, ad_duration', 'safe', 'on'=>'search'),
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
			'recon_file' => 'Recon File',
			'log_campaign_name' => 'Log Campaign Name',
			'log_station_name' => 'Log Station Name',
			'log_date' => 'Log Date',
			'log_time' => 'Log Time',
			'rf_company_id' => 'Rf Company',
			'log_company_name' => 'Log Company Name',
			'rf_brand_id' => 'Rf Brand',
			'log_brand_name' => 'Log Brand Name',
			'log_sub_brand_name' => 'Log Sub Brand Name',
			'rf_incantation_id' => 'Rf Incantation',
			'rf_station_id' => 'Rf Station',
			'adtype_name' => 'Adtype Name',
			'rf_entry_type_id' => 'Rf Entry Type',
			'ad_duration' => 'Ad Duration',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('recon_file',$this->recon_file);
		$criteria->compare('log_campaign_name',$this->log_campaign_name,true);
		$criteria->compare('log_station_name',$this->log_station_name,true);
		$criteria->compare('log_date',$this->log_date,true);
		$criteria->compare('log_time',$this->log_time,true);
		$criteria->compare('rf_company_id',$this->rf_company_id,true);
		$criteria->compare('log_company_name',$this->log_company_name,true);
		$criteria->compare('rf_brand_id',$this->rf_brand_id,true);
		$criteria->compare('log_brand_name',$this->log_brand_name,true);
		$criteria->compare('log_sub_brand_name',$this->log_sub_brand_name,true);
		$criteria->compare('rf_incantation_id',$this->rf_incantation_id,true);
		$criteria->compare('rf_station_id',$this->rf_station_id,true);
		$criteria->compare('adtype_name',$this->adtype_name,true);
		$criteria->compare('rf_entry_type_id',$this->rf_entry_type_id);
		$criteria->compare('ad_duration',$this->ad_duration);

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
	 * @return ReconTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getUnresolvedStations($uploadid){
		$sql = "SELECT DISTINCT recon_temp.log_station_name FROM recon_temp WHERE recon_file=$uploadid AND rf_station_id IS NULL";
		$stations = ReconTemp::model()->findAllBySql($sql);
		if($stations){
			return $stations;
		}else{
			return false;
		}
	}

	public function getUnresolvedAdtypes($uploadid){
		$sql = "SELECT DISTINCT recon_temp.adtype_name FROM recon_temp WHERE recon_file=$uploadid AND rf_entry_type_id IS NULL";
		$stations = ReconTemp::model()->findAllBySql($sql);
		if($stations){
			return $stations;
		}else{
			return false;
		}
	}

	public function getUnresolvedCompanies($uploadid){
		$sql = "SELECT DISTINCT recon_temp.log_company_name FROM recon_temp WHERE recon_file=$uploadid AND rf_company_id IS NULL";
		$stations = ReconTemp::model()->findAllBySql($sql);
		if($stations){
			return $stations;
		}else{
			return false;
		}
	}

	public static function RFCompanies($company_name){
		$company_name = trim(str_replace(' ', '%', str_ireplace(' LTD', ' ', str_ireplace(' limited', ' ', $company_name))));
		$sql = "SELECT * FROM user_table WHERE company_name LIKE '%$company_name%' ORDER BY company_name";
		$companies = UserTable::model()->findAllBySql($sql);
		if(!$companies){
			$sql = "SELECT * FROM user_table ORDER BY company_name";
			$companies = UserTable::model()->findAllBySql($sql);
		}
		return CHtml::listData($companies,'company_id','company_name');
	}

	public static function LogCompanies($id){
		$sql = "SELECT company_id,company_name FROM user_table
		INNER JOIN recon_temp ON recon_temp.rf_company_id=user_table.company_id 
		WHERE recon_file = $id
		ORDER BY company_name";
		$companies = UserTable::model()->findAllBySql($sql);
		return CHtml::listData($companies,'company_id','company_name');
	}
}
