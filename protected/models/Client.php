<?php
/* 
Small business administrator
Copyright (C) 2013 JULIO ALEXANDER GUEVARA MARULANDA

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php

/**
 * This is the model class for table "client".
 *
 * The followings are the available columns in table 'client':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $phone
 * @property string $mobile
 *
 * The followings are the available model relations:
 * @property ClientInvoice[] $clientInvoices
 */
class Client extends CActiveRecord {
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['Client_def_id'];
		if (!$this->code) $this->code=Yii::app()->params['Client_def_code'];
		if (!$this->name) $this->name=Yii::app()->params['Client_def_name'];
		if (!$this->description) $this->description=Yii::app()->params['Client_def_description'];
		if (!$this->address) $this->address=Yii::app()->params['Client_def_address'];
		if (!$this->phone) $this->phone=Yii::app()->params['Client_def_phone'];
		if (!$this->mobile) $this->mobile=Yii::app()->params['Client_def_mobile'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'Client_def_id'=>null,
		//'Client_def_code'=>null,
		//'Client_def_name'=>null,
		//'Client_def_description'=>null,
		//'Client_def_address'=>null,
		//'Client_def_phone'=>null,
		//'Client_def_mobile'=>null,
		parent::afterConstruct();
	}

	public function behaviors() {
		return array(
			'RememberFilters' => array(
				'class' => 'RememberFilters',
				'defaults'=>array(), /* optional line */
				'defaultStickOnClear'=>false /* optional line */
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Client the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('code', 'length', 'max'=>15),
			array('name', 'length', 'max'=>75),
			array('description, address', 'length', 'max'=>100),
			array('phone', 'length', 'max'=>20),
			array('mobile', 'length', 'max'=>50),
			array('code','unique','attributeName'=>'code','className'=>'client','allowEmpty'=>true),
			array('name','unique','attributeName'=>'name','className'=>'client','allowEmpty'=>false),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, description, address, phone, mobile', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clientInvoices' => array(self::HAS_MANY, 'ClientInvoice', 'client'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.Client.ID'),
			'code'=>Yii::t('app','model.Client.Code'),
			'name'=>Yii::t('app','model.Client.Name'),
			'description'=>Yii::t('app','model.Client.Description'),
			'address'=>Yii::t('app','model.Client.Address'),
			'phone'=>Yii::t('app','model.Client.Phone'),
			'mobile'=>Yii::t('app','model.Client.Mobile'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('mobile',$this->mobile,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'id DESC',),
		));
	}

	public static function getList(){
		$criteria=new CDbCriteria;
		$criteria->select="id,CONCAT(IF(code<>'', CONCAT(code,' - '), ''),name) name";
		$criteria->order='name ASC';
		return CHtml::listData(Client::model()->findAll($criteria),'id','name');
	}

	public static function getCount() {
		$count = Client::model()->count();
		return $count;
	}
}