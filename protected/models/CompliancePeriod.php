<?php

/**
 * This is the model class for table "compliance_period".
 *
 * The followings are the available columns in table 'compliance_period':
 * @property integer $id
 * @property integer $schedule_id
 * @property string $addate
 * @property string $timeblock
 * @property string $station_name
 * @property integer $station_id
 * @property string $adtype
 * @property integer $entry_type_id
 * @property integer $expected_ads
 */
class CompliancePeriod extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'compliance_period';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('schedule_id, addate, timeblock, station_name, adtype, expected_ads', 'required'),
			array('schedule_id, station_id, expected_ads', 'numerical', 'integerOnly'=>true),
			array('timeblock, entry_type_id', 'length', 'max'=>200),
			array('station_name, adtype', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, schedule_id, addate, timeblock, station_name, station_id, adtype, entry_type_id, expected_ads', 'safe', 'on'=>'search'),
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
			'schedule_id' => 'Schedule',
			'addate' => 'Addate',
			'timeblock' => 'Timeblock',
			'station_name' => 'Station Name',
			'station_id' => 'Station',
			'adtype' => 'Adtype',
			'entry_type_id' => 'Entry Type',
			'expected_ads' => 'Expected Ads',
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
		$criteria->compare('schedule_id',$this->schedule_id);
		$criteria->compare('addate',$this->addate,true);
		$criteria->compare('timeblock',$this->timeblock,true);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('adtype',$this->adtype,true);
		$criteria->compare('entry_type_id',$this->entry_type_id,true);
		$criteria->compare('expected_ads',$this->expected_ads);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->compliancedb;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompliancePeriod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getUnresolvedStations($schedule_id){
		$sql = "SELECT DISTINCT compliance_period.station_name FROM compliance_period WHERE schedule_id=$schedule_id AND (station_id IS NULL OR station_id=0)";
		$stations = CompliancePeriod::model()->findAllBySql($sql);
		if($stations){
			return $stations;
		}else{
			return false;
		}
	}

	public function getUnresolvedAdtypes($schedule_id){
		$sql = "SELECT DISTINCT compliance_period.adtype FROM compliance_period WHERE schedule_id=$schedule_id AND (entry_type_id IS NULL OR entry_type_id=0)";
		$stations = CompliancePeriod::model()->findAllBySql($sql);
		if($stations){
			return $stations;
		}else{
			return false;
		}
	}
}
