<?php

/**
* DjmentionsEntryTypes Model Class
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
 * This is the model class for table "djmentions_entry_types".
 *
 * The followings are the available columns in table 'djmentions_entry_types':
 * @property integer $auto_id
 * @property string $entry_type
 * @property string $entry_type_id
 */
class DjmentionsEntryTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DjmentionsEntryTypes the static model class
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
		return 'djmentions_entry_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('entry_type', 'length', 'max'=>100),
			array('entry_type_id', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('auto_id, entry_type, entry_type_id', 'safe', 'on'=>'search'),
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
			'auto_id' => 'Auto',
			'entry_type' => 'Entry Type',
			'entry_type_id' => 'Entry Type',
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

		$criteria->compare('auto_id',$this->auto_id);
		$criteria->compare('entry_type',$this->entry_type,true);
		$criteria->compare('entry_type_id',$this->entry_type_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function EntryTypes($category){
		$sql = "SELECT entry_type_id,entry_type from djmentions_entry_types WHERE category=$category order by entry_type_id asc";
		return CHtml::listData(DjmentionsEntryTypes::model()->findAllBySql($sql),'entry_type_id','entry_type');
	}

	public static function EntryTypeCheckBoxes(){
		$array_list = DjmentionsEntryTypes::EntryTypes();
		$list = '';
		foreach ($array_list as $key =>$value) {
			$list .= '<div class="col-md-4 clearfix"><div><input type="checkbox" value="'.$key.'" id="roundedOne" name="adtype[]">&nbsp;'.$value.'</div></div>';
		}
		return $list;
	}

	public static function RFEntryTypes($entry_type){
		$entry_type = trim( str_ireplace('-', ' ', str_ireplace('tv', ' ', str_ireplace('fm', ' ', str_ireplace('radio', ' ',trim($entry_type)) ))) );
		$sql = "SELECT * FROM djmentions_entry_types WHERE entry_type LIKE '%$entry_type%' ORDER BY entry_type";
		$adtypes = DjmentionsEntryTypes::model()->findAllBySql($sql);
		if(!$adtypes){
			$sql = "SELECT * FROM djmentions_entry_types ORDER BY entry_type";
			$adtypes = DjmentionsEntryTypes::model()->findAllBySql($sql);
		}
		return CHtml::listData($adtypes,'entry_type_id','entry_type');
	}

	public static function GetEntryTypes($id){
		$sql = "SELECT DISTINCT djmentions_entry_types.entry_type_id, djmentions_entry_types.entry_type 
		FROM djmentions_entry_types 
		INNER JOIN recon_temp ON recon_temp.rf_entry_type_id=djmentions_entry_types.entry_type_id
		WHERE recon_file = $id
		GROUP BY recon_temp.rf_entry_type_id";
		return CHtml::listData(DjmentionsEntryTypes::model()->findAllBySql($sql),'entry_type_id','entry_type');
	}
}
