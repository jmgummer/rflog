<?php

/**
* Station Model Class
*
* @package     Anvil
* @subpackage  Models
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

/**
 * This is the model class for table "station".
 *
 * The followings are the available columns in table 'station':
 * @property integer $station_id
 * @property string $station_name
 * @property string $karf_name
 * @property integer $frequency
 * @property integer $server_id
 * @property integer $country_id
 * @property integer $region_id
 * @property string $station_code
 * @property string $country_code
 * @property string $station_type
 * @property string $online
 * @property integer $serverport
 * @property string $station_status
 * @property string $contact_person
 * @property string $address
 * @property string $email
 * @property string $language_type
 */
class Station extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Station the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->forgedb;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'station';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('karf_name, frequency, server_id, serverport, contact_person, address, email', 'required'),
			array('frequency, server_id, country_id, region_id, serverport, subscription_req', 'numerical', 'integerOnly'=>true),
			array('station_name, karf_name, contact_person, address, email', 'length', 'max'=>100),
			array('station_code', 'length', 'max'=>3),
			array('country_code', 'length', 'max'=>2),
			array('station_type', 'length', 'max'=>5),
			array('online, station_status, language_type', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('station_id, station_name, karf_name, frequency, server_id, country_id, region_id, station_code, country_code, station_type, online, serverport, station_status, contact_person, address, email, language_type', 'safe', 'on'=>'search'),
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
			'station_id' => 'Station',
			'station_name' => 'Station Name',
			'karf_name' => 'Karf Name',
			'frequency' => 'Frequency',
			'server_id' => 'Server',
			'country_id' => 'Country',
			'region_id' => 'Region',
			'station_code' => 'Station Code',
			'country_code' => 'Country Code',
			'station_type' => 'Station Type',
			'online' => 'Online',
			'serverport' => 'Serverport',
			'station_status' => 'Station Status',
			'contact_person' => 'Contact Person',
			'address' => 'Address',
			'email' => 'Email',
			'language_type' => 'Language Type',
			'subscription_req'=>'Subscription Required'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('station_name',$this->station_name,true);
		$criteria->compare('karf_name',$this->karf_name,true);
		$criteria->compare('frequency',$this->frequency);
		$criteria->compare('server_id',$this->server_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('subscription_req',$this->subscription_req);
		$criteria->compare('station_code',$this->station_code,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('station_type',$this->station_type,true);
		$criteria->compare('online',$this->online,true);
		$criteria->compare('serverport',$this->serverport);
		$criteria->compare('station_status',$this->station_status,true);
		$criteria->compare('contact_person',$this->contact_person,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('language_type',$this->language_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function AllStations(){
		$sql = "SELECT * FROM station WHERE station.online=1  and station.station_status=1 ORDER BY station_name";
		return CHtml::listData(Station::model()->findAllBySql($sql),'station_id','station_name');
	}

	public static function RFStations($station_name){
		$station_name = trim(str_ireplace(' tv', ' ', str_ireplace(' fm', ' ', $station_name)));
		$sql = "SELECT * FROM station WHERE station.station_name LIKE '%$station_name%' ORDER BY station_name";
		$stations = Station::model()->findAllBySql($sql);
		if(!$stations){
			$sql = "SELECT * FROM station WHERE station.online=1  and station.station_status=1 ORDER BY station_name";
			$stations = Station::model()->findAllBySql($sql);
		}
		return CHtml::listData($stations,'station_id','station_name');
	}

	public static function StationList(){
		$sql_station = "SELECT * FROM station WHERE station.online=1  and station.station_status=1 ORDER BY station_name";
		return CHtml::listData(Station::model()->findAllBySql($sql_station),'station_id','station_name');
	}
}