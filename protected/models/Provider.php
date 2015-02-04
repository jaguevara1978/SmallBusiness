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
 * This is the model class for table "provider".
 *
 * The followings are the available columns in table 'provider':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $phone
 * @property string $mobile
 *
 * The followings are the available model relations:
 * @property ProviderInvoice[] $providerInvoices
 */
class Provider extends CActiveRecord {
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['Provider_def_id'];
		if (!$this->code) $this->code=Yii::app()->params['Provider_def_code'];
		if (!$this->name) $this->name=Yii::app()->params['Provider_def_name'];
		if (!$this->description) $this->description=Yii::app()->params['Provider_def_description'];
		if (!$this->address) $this->address=Yii::app()->params['Provider_def_address'];
		if (!$this->phone) $this->phone=Yii::app()->params['Provider_def_phone'];
		if (!$this->mobile) $this->mobile=Yii::app()->params['Provider_def_mobile'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'Provider_def_id'=>null,
		//'Provider_def_code'=>null,
		//'Provider_def_name'=>null,
		//'Provider_def_description'=>null,
		//'Provider_def_address'=>null,
		//'Provider_def_phone'=>null,
		//'Provider_def_mobile'=>null,
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
	 * @return Provider the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'provider';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('name','required'),
			array('id','numerical','integerOnly'=>true),
			array('id','length','max'=>5),
			array('code','length','max'=>15),
			array('name','length','max'=>75),
			array('description,address','length','max'=>100),
			array('phone','length','max'=>20),
			array('mobile','length','max'=>50),
			array('code','unique','attributeName'=>'code','className'=>'Provider','allowEmpty'=>true),
			array('name','unique','attributeName'=>'name','className'=>'Provider','allowEmpty'=>false),
			
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
			'providerInvoices' => array(self::HAS_MANY, 'ProviderInvoice', 'provider'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.Provider.ID'),
			'code'=>Yii::t('app','model.Provider.Code'),
			'name'=>Yii::t('app','model.Provider.Name'),
			'description'=>Yii::t('app','model.Provider.Description'),
			'address'=>Yii::t('app','model.Provider.Address'),
			'phone'=>Yii::t('app','model.Provider.Phone'),
			'mobile'=>Yii::t('app','model.Provider.Mobile'),
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.address',$this->address,true);
		$criteria->compare('t.phone',$this->phone,true);
		$criteria->compare('t.mobile',$this->mobile,true);
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
		return CHtml::listData(Provider::model()->findAll($criteria),'id','name');
	}

	public static function getCount() {
		$count = Provider::model()->count();
		return $count;
	}

	public function isEditable() {
		return true;
	}
}