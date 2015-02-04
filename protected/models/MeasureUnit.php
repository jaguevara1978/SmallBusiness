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
 * This is the model class for table "measure_unit".
 *
 * The followings are the available columns in table 'measure_unit':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $eq_reference
 * @property string $reference
 *
 * The followings are the available model relations:
 * @property Product[] $products
 */
class MeasureUnit extends CActiveRecord {
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['MeasureUnit_def_id'];
		if (!$this->code) $this->code=Yii::app()->params['MeasureUnit_def_code'];
		if (!$this->name) $this->name=Yii::app()->params['MeasureUnit_def_name'];
		if (!$this->eq_reference) $this->eq_reference=Yii::app()->params['MeasureUnit_def_eq_reference'];
		if (!$this->reference) $this->reference=Yii::app()->params['MeasureUnit_def_reference'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'MeasureUnit_def_id'=>null,
		//'MeasureUnit_def_code'=>null,
		//'MeasureUnit_def_name'=>null,
		//'MeasureUnit_def_eq_reference'=>null,
		//'MeasureUnit_def_reference'=>null,
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

	protected function beforeSave() {
		if($this->reference==1) {
			$eqRef=$this->eq_reference;
			if($this->isNewRecord) $this->eq_reference=1;
			$command=Yii::app()->db->createCommand(
						"UPDATE measure_unit SET reference=0, eq_reference = COALESCE(ROUND(eq_reference / ".$eqRef.",2),0)");
			$command->execute(); // execute the non-query SQL
		}
		return parent::beforeSave();		
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MeasureUnit the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'measure_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('code,name,eq_reference,reference','required'),
			array('id','numerical','integerOnly'=>true),
			array('eq_reference','numerical','min'=>0),
			array('id','length','max'=>3),
			array('code','length','max'=>5),
			array('name','length','max'=>45),
			array('eq_reference','length','max'=>6),
			array('reference','length','max'=>1),
			array('code','unique','attributeName'=>'code','className'=>'MeasureUnit','allowEmpty'=>false),
			array('name','unique','attributeName'=>'name','className'=>'MeasureUnit','allowEmpty'=>false),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, eq_reference, reference', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'products' => array(self::HAS_MANY, 'Product', 'measure_unit'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.MeasureUnit.ID'),
			'code'=>Yii::t('app','model.MeasureUnit.Code'),
			'name'=>Yii::t('app','model.MeasureUnit.Name'),
			'eq_reference'=>Yii::t('app','model.MeasureUnit.Eq Reference'),
			'reference'=>Yii::t('app','model.MeasureUnit.Reference'),
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.eq_reference',$this->eq_reference,true);
		$criteria->compare('t.reference',$this->reference,true);
		return $criteria;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=$this->criteria();
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'id DESC',),
		));
	}

	public static function getList(){
		$criteria=new CDbCriteria;
		$criteria->select="id,CONCAT(IF(code<>'', CONCAT(code,' - '), ''),name) name";
		$criteria->order='name ASC';
		return CHtml::listData(MeasureUnit::model()->findAll($criteria),'id','name');
	}

	public static function getCount() {
		$count = MeasureUnit::model()->count();
		return $count;
	}

	public function isEditable() {
		return true;
	}

	public static function findReference() {
		$criteria=new CDbCriteria();
		$criteria->compare('reference', '1');
		$criteria->limit=1;
		return MeasureUnit::model()->find($criteria);
	}
}