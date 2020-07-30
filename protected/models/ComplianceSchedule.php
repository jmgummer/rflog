<?php

/**
 * This is the model class for table "compliance_schedule".
 *
 * The followings are the available columns in table 'compliance_schedule':
 * @property integer $id
 * @property integer $company_id
 * @property integer $brand_id
 * @property integer $admonth
 * @property integer $adyear
 * @property string $mediaschedule
 * @property string $country
 * @property string $dateuploaded
 * @property integer $runstate
 */
class ComplianceSchedule extends CActiveRecord
{
	public $actions;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'compliance_schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('compliance_name, brand_id, mediaschedule, country', 'required'),
			array('company_id, admonth, adyear, runstate', 'numerical', 'integerOnly'=>true),
			array('mediaschedule, compliance_name', 'length', 'max'=>200),
			array('country', 'length', 'max'=>4),
			array('dateuploaded', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, brand_id, admonth, adyear, mediaschedule, country, dateuploaded, runstate, compliance_name', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'brand_id' => 'Brand',
			'admonth' => 'Admonth',
			'adyear' => 'Adyear',
			'mediaschedule' => 'Mediaschedule',
			'country' => 'Country',
			'dateuploaded' => 'Dateuploaded',
			'runstate' => 'Runstate',
			'compliance_name' => 'Compliance Name'
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('admonth',$this->admonth);
		$criteria->compare('adyear',$this->adyear);
		$criteria->compare('mediaschedule',$this->mediaschedule,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('dateuploaded',$this->dateuploaded,true);
		$criteria->compare('runstate',$this->runstate);
		$criteria->compare('compliance_name',$this->compliance_name,true);

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
	 * @return ComplianceSchedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
