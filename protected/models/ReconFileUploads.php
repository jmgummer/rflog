<?php

/**
 * This is the model class for table "recon_file_uploads".
 *
 * The followings are the available columns in table 'recon_file_uploads':
 * @property integer $id
 * @property string $filename
 * @property string $upload_date
 * @property integer $station_id
 * @property integer $logtype
 * @property integer $recon_state
 */
class ReconFileUploads extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recon_file_uploads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filename, upload_date', 'required'),
			array('station_id, logtype, recon_state', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, filename, upload_date, station_id, logtype, recon_state', 'safe', 'on'=>'search'),
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
			'filename' => 'Filename',
			'upload_date' => 'Upload Date',
			'station_id' => 'Station',
			'logtype' => 'Logtype',
			'recon_state' => 'Recon State',
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
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('upload_date',$this->upload_date,true);
		$criteria->compare('station_id',$this->station_id);
		$criteria->compare('logtype',$this->logtype);
		$criteria->compare('recon_state',$this->recon_state);

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
	 * @return ReconFileUploads the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStation(){
		if($this->station_id!=null){
			$station = Station::model()->find('station_id=:a', array(':a'=>$this->station_id));
			if($station){
				return $station->station_name;
			}else{
				return 'Not Found';
			}
		}else{
			return 'Multiple Stations';
		}
	}

	public function getRunState(){
		if($this->recon_state==null || $this->recon_state==0){
			echo "Pending";
		}else{
			echo "Complete";
		}
	}

	public function getActions(){
		$edit_link = Yii::app()->createUrl('home/process/'.$this->id);
		echo $link = "<a href='$edit_link' class=''>Process</a>";
	}
}
