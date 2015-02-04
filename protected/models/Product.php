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
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $measure_unit
 * @property integer $custom_order
 * @property string $default_qty
 * @property string $default_value
 * @property string $stock_movement
 *
 * The followings are the available model relations:
 * @property ClientInvoiceDetail[] $clientInvoiceDetails
 * @property MeasureUnit $measureUnit
 * @property ProviderInvoiceDetail[] $providerInvoiceDetails
 * @property Stock[] $stocks
 */
class Product extends CActiveRecord {
	public $stock_quantity;
	public $stock_detail_value;
	public $stock_manual_value;

	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['Product_def_id'];
		if (!$this->code) $this->code=Yii::app()->params['Product_def_code'];
		if (!$this->name) $this->name=Yii::app()->params['Product_def_name'];
		if (!$this->measure_unit) $this->measure_unit=Yii::app()->params['Product_def_measure_unit'];
		if (!$this->custom_order) $this->custom_order=$this->getNextOrder();
		if (!$this->default_qty) $this->default_qty=Yii::app()->params['Product_def_default_qty'];
		if (!$this->default_value) $this->default_value=Yii::app()->params['Product_def_default_value'];
		if (!$this->stock_movement) $this->stock_movement=Yii::app()->params['Product_def_stock_movement'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'Product_def_id'=>null,
		//'Product_def_code'=>null,
		//'Product_def_name'=>null,
		//'Product_def_measure_unit'=>null,
		//'Product_def_custom_order'=>null,
		//'Product_def_default_qty'=>null,
		//'Product_def_default_value'=>null,
		//'Product_def_stock_movement'=>null,
		parent::afterConstruct();
	}

	public function behaviors() {
		return array(
			'RememberFilters' => array(
				'class' => 'RememberFilters',
				'defaults'=>array(), /* optional line */
				'defaultStickOnClear'=>false /* optional line */
			),
// 			'ValidateDeletionBehavior' => array(
// 					'class' => 'ValidateDeletionBehavior',
// 					'relations' => array('ClientInvoiceDetails'),
// 			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('code,name,measure_unit,stock_movement','required'),
			array('id,measure_unit,custom_order','numerical','integerOnly'=>true),
			array('default_qty,default_value','numerical','min'=>0),
			array('id,measure_unit','length','max'=>3),
			array('code','length','max'=>5),
			array('name','length','max'=>45),
			array('custom_order','length','max'=>4),
			array('default_qty,default_value','length','max'=>11),
			array('stock_movement','length','max'=>1),
			array('code','unique','attributeName'=>'code','className'=>'Product','allowEmpty'=>false),
			array('name','unique','attributeName'=>'name','className'=>'Product','allowEmpty'=>false),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, measure_unit, custom_order, default_qty, default_value, stock_movement, stock_quantity', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clientInvoiceDetails' => array(self::HAS_MANY, 'ClientInvoiceDetail', 'product'),
			'measureUnit' => array(self::BELONGS_TO, 'MeasureUnit', 'measure_unit'),
			'providerInvoiceDetails' => array(self::HAS_MANY, 'ProviderInvoiceDetail', 'product'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'product'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.Product.ID'),
			'code'=>Yii::t('app','model.Product.Code'),
			'name'=>Yii::t('app','model.Product.Name'),
			'measure_unit'=>Yii::t('app','model.Product.Measure Unit'),
			'custom_order'=>Yii::t('app','model.Product.Custom Order'),
			'default_qty'=>Yii::t('app','model.Product.Default Qty'),
			'default_value'=>Yii::t('app','model.Product.Default Value'),
			'stock_movement'=>Yii::t('app','model.Product.Stock Movement'),
			'stock_quantity'=>Yii::t('app','model.Product.Stock Quantity'),
			'stock_detail_value'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
			'stock_manual_value'=>Yii::t('app','model.ClientInvoiceDetail.totalValue').' Manual',
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;
	
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.measure_unit',$this->measure_unit);
		$criteria->compare('t.custom_order',$this->custom_order);
		$criteria->compare('t.default_qty',$this->default_qty,true);
		$criteria->compare('t.default_value',$this->default_value,true);
		$criteria->compare('t.stock_movement',$this->stock_movement,true);
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

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search_stock() {
		$criteria=$this->criteria();
		$criteria->compare('product_stock(t.id)',$this->stock_quantity,true);
		$criteria->select.=',FORMAT(product_stock(t.id),2) stock_quantity
							,FORMAT(product_stock_detail_value(t.id),2) stock_detail_value
							,FORMAT(product_stock_manual_value(t.id),2) stock_manual_value';
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>array('pageSize'=>Product::getCount()),
				'sort'=>array('defaultOrder'=>'name ASC',),
		));
	}

	public static function getList(){
		$criteria=new CDbCriteria;
		$criteria->select="t.id,CONCAT(CONCAT('(',mu.code,') '),IF(t.code<>'', CONCAT(t.code,' - '), ''),t.name) name";
		$criteria->order='t.name ASC';
		$criteria->join = 'LEFT OUTER JOIN measure_unit mu
							on mu.id = t.measure_unit';
		return CHtml::listData(Product::model()->findAll($criteria),'id','name');
	}
	
	public static function getCount() {
		$count = Product::model()->count();
		if($count<Yii::app()->params['General_pageSize']) $count=Yii::app()->params['General_pageSize'];
		return $count;
	}
	
	public static function getNextOrder() {
		$criteria=new CDbCriteria;
		$criteria->select='COALESCE(MAX(custom_order),0) + 10 AS custom_order';
		$criteria->order='name ASC';
		return Product::model()->find($criteria)->custom_order;
	}
	
	public static function totalProvider($criteria)
    	{
        $totalDetail=0;
        $totalManual=0;
	$criteria->select.=',product_stock_detail_value(t.id) stock_detail_value
							,product_stock_manual_value(t.id) stock_manual_value';

        $provider = Product::model()->findAll($criteria);

        foreach($provider as $data)
        {
            $totalDetail += $data->stock_detail_value;
            $totalManual += $data->stock_manual_value;
        }
        $totalDetail = Yii::app()->numberFormatter->formatDecimal($totalDetail);
        $totalManual = Yii::app()->numberFormatter->formatDecimal($totalManual);
        return $totalDetail.'_'.$totalManual;
   	}

}