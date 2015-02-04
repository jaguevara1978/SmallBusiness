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
 * This is the model class for table "status".
 *
 * The followings are the available columns in table 'status':
 * @property integer $id
 * @property string $name
 * @property string $use_type
 *
 * The followings are the available model relations:
 * @property ClientInvoice[] $clientInvoices
 * @property ProviderInvoice[] $providerInvoices
 */
class Status extends CActiveRecord {
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
	 * @return Status the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, use_type', 'required'),
			array('name', 'length', 'max'=>45),
			array('use_type', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, use_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clientInvoices' => array(self::HAS_MANY, 'ClientInvoice', 'status'),
			'providerInvoices' => array(self::HAS_MANY, 'ProviderInvoice', 'status'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
	'id'=>Yii::t('app','model.Status.ID'),
	'name'=>Yii::t('app','model.Status.Name'),
	'use_type'=>Yii::t('app','model.Status.Use Type'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('use_type',$this->use_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'id DESC',),
		));
	}
		
	public static function getList($use=null){
		if ($use!==null) {
			$criteria = new CDbCriteria;
			$criteria->compare('use_type',$use);
			$criteria->order='id asc';
			return CHtml::listData(Status::model()->findAll($criteria),'id','name');
		}
		return CHtml::listData(Status::model()->findAll(array('order'=>'id')),'id','name');
	}
	
	public static function getCount() {
		$count = Status::model()->count();
		return $count;
	}	
}