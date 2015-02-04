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
/**
 * This is the model class for table "provider_invoice_detail".
 *
 * The followings are the available columns in table 'provider_invoice_detail':
 * @property integer $id
 * @property integer $provider_invoice
 * @property string $quantity
 * @property integer $product
 * @property string $unit_value
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Product $product0
 * @property ProviderInvoice $providerInvoice
 */
class ProviderInvoiceDetail extends CActiveRecord {
	public $sumTotalValue;
	public $sumTotalWeight;

	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ProviderInvoiceDetail_def_id'];
		if (!$this->provider_invoice) $this->provider_invoice=Yii::app()->params['ProviderInvoiceDetail_def_provider_invoice'];
		if (!$this->quantity) $this->quantity=Yii::app()->params['ProviderInvoiceDetail_def_quantity'];
		if (!$this->product) $this->product=Yii::app()->params['ProviderInvoiceDetail_def_product'];
		if (!$this->unit_value) $this->unit_value=Yii::app()->params['ProviderInvoiceDetail_def_unit_value'];
		if (!$this->notes) $this->notes=Yii::app()->params['ProviderInvoiceDetail_def_notes'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ProviderInvoiceDetail_def_id'=>null,
		//'ProviderInvoiceDetail_def_provider_invoice'=>null,
		//'ProviderInvoiceDetail_def_quantity'=>null,
		//'ProviderInvoiceDetail_def_product'=>null,
		//'ProviderInvoiceDetail_def_unit_value'=>null,
		//'ProviderInvoiceDetail_def_notes'=>null,
		parent::afterConstruct();
	}

	protected function beforeSave() {
		if (!$this->quantity) $this->quantity=Product::model()->findByPk($this->product)->default_qty;
		if (!$this->unit_value) $this->unit_value=Product::model()->findByPk($this->product)->default_value;
		return parent::beforeSave();
	}

	protected function afterSave() {
		if($this->product0->stock_movement==1
			&& $this->providerInvoice->status>=Yii::app()->params['ProviderInvoice_stock_status'])
			if(!Stock::model()->createFromPID($this))
				$this->addError('quantity','Error en Stock::model()->createFromPID($this), Called from ProviderInvoiceDetail::beforeSave()');
		parent::afterSave();
	}

	protected function beforeDelete() {
		if($this->product0->stock_movement==1)
			if(!Stock::model()->deleteFromPID($this))
				$this->addError('quantity','Error en Stock::model()->deleteFromPID($this), Called from ProviderInvoiceDetail::beforeDelete()');
		return parent::beforeSave();
	}

	protected function afterDelete() {
		parent::afterSave();
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
	 * @return ProviderInvoiceDetail the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'provider_invoice_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product', 'UnqAttrValidate', 'with'=>'provider_invoice,product,unit_value'),
			array('provider_invoice,product','required'),
			array('id,provider_invoice,product','numerical','integerOnly'=>true),
			array('quantity,unit_value','numerical','min'=>0),
			array('id,provider_invoice,quantity,unit_value','length','max'=>11),
			array('product','length','max'=>3),
			array('notes','length','max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, provider_invoice, quantity, product, unit_value, notes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'product0' => array(self::BELONGS_TO, 'Product', 'product'),
			'providerInvoice' => array(self::BELONGS_TO, 'ProviderInvoice', 'provider_invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ProviderInvoiceDetail.ID'),
			'provider_invoice'=>Yii::t('app','model.ProviderInvoiceDetail.Provider Invoice'),
			'quantity'=>Yii::t('app','model.ProviderInvoiceDetail.Quantity'),
			'product'=>Yii::t('app','model.ProviderInvoiceDetail.Product'),
			'unit_value'=>Yii::t('app','model.ProviderInvoiceDetail.Unit Value'),
			'notes'=>Yii::t('app','model.ProviderInvoiceDetail.Notes'),
			'totalValue'=>Yii::t('app','model.ProviderInvoiceDetail.totalValue'),
			'sumTotalValue'=>Yii::t('app','model.ProviderInvoiceDetail.sumTotalValue'),
		);
	}
	
	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.provider_invoice',$this->provider_invoice);
		$criteria->compare('t.quantity',$this->quantity,true);
		$criteria->compare('t.product',$this->product);
		$criteria->compare('t.unit_value',$this->unit_value,true);
		$criteria->compare('t.notes',$this->notes,true);
		return $criteria;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($showQtyZero=true) {
		$criteria=$this->criteria();
		$criteria->with = array('product0');
		if(!$showQtyZero) $criteria->addCondition('quantity > 0');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>Product::getCount()),
			'sort'=>array('defaultOrder'=>'product0.custom_order ASC',),
		));
	}

	public function totalProvider() {
		$criteria=$this->criteria();
		$totalWeightStr=Yii::t('app','general.label.totalWeightFooter');
		$refUnit=' ('.strtolower(MeasureUnit::findReference()->code).'s)';
		$criteria->select="FORMAT(COALESCE(SUM(t.quantity * t.unit_value),0),0) sumTotalValue
						,CONCAT('".$totalWeightStr." ',FORMAT(COALESCE(SUM(t.quantity * mu.eq_reference),0),0),'".$refUnit."') sumTotalWeight";
		$criteria->join = 'INNER JOIN product p
							ON p.id = t.product
							INNER JOIN measure_unit mu
							ON mu.id = p.measure_unit';
		return ProviderInvoiceDetail::model()->find($criteria);
	}

	public static function getCount($headerId=null) {
		if($headerId) {
			$condition='provider_invoice=:id AND quantity > 0';
			$parms=array(':id'=>$headerId);
		}
		return ProviderInvoiceDetail::model()->count($condition,$parms);
	}

	public function isEditable() {
		if($this->providerInvoice->status!==Yii::app()->params['ProviderInvoice_final_status'])
			return true;
		return true;
	}


	/********PROTECTED CODE FROM HERE************/
}