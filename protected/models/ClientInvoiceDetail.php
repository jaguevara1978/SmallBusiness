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
 * This is the model class for table "client_invoice_detail".
 *
 * The followings are the available columns in table 'client_invoice_detail':
 * @property integer $id
 * @property integer $client_invoice
 * @property string $quantity
 * @property integer $product
 * @property string $unit_value
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Product $product0
 * @property ClientInvoice $clientInvoice
 */
class ClientInvoiceDetail extends CActiveRecord {
	public $sumTotalValue;
	public $sumTotalWeight;
	
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ClientInvoiceDetail_def_id'];
		if (!$this->client_invoice) $this->client_invoice=Yii::app()->params['ClientInvoiceDetail_def_client_invoice'];
		if (!$this->quantity) $this->quantity=Yii::app()->params['ClientInvoiceDetail_def_quantity'];
		if (!$this->product) $this->product=Yii::app()->params['ClientInvoiceDetail_def_product'];
		if (!$this->unit_value) $this->unit_value=Yii::app()->params['ClientInvoiceDetail_def_unit_value'];
		if (!$this->notes) $this->notes=Yii::app()->params['ClientInvoiceDetail_def_notes'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ClientInvoiceDetail_def_id'=>null,
		//'ClientInvoiceDetail_def_client_invoice'=>null,
		//'ClientInvoiceDetail_def_quantity'=>null,
		//'ClientInvoiceDetail_def_product'=>null,
		//'ClientInvoiceDetail_def_unit_value'=>null,
		//'ClientInvoiceDetail_def_notes'=>null,
		parent::afterConstruct();
	}
	
	protected function beforeSave() {
		if (!$this->quantity) $this->quantity=Product::model()->findByPk($this->product)->default_qty;
		if (!$this->unit_value) $this->unit_value=Product::model()->findByPk($this->product)->default_value;
		return parent::beforeSave();
	}

	protected function afterSave() {
		if($this->product0->stock_movement==1 
			&& $this->clientInvoice->status>=Yii::app()->params['ClientInvoice_stock_status'])
			if(!Stock::model()->createFromCID($this))
				$this->addError('quantity','Error en Stock::model()->createFromCID($this), Called from ClientInvoiceDetail::afterSave()');
		parent::afterSave();
	}

	protected function beforeDelete() {
		if($this->product0->stock_movement==1)
			if(!Stock::model()->deleteFromCID($this))
				$this->addError('quantity','Error en Stock::model()->deleteFromCID($this), Called from ClientInvoiceDetail::beforeDelete()');
		return parent::beforeDelete();
	}

	protected function afterDelete() {
		parent::afterDelete();
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
	 * @return ClientInvoiceDetail the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'client_invoice_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product', 'UnqAttrValidate', 'with'=>'client_invoice,product,unit_value',),
			array('client_invoice, product', 'required'),
			array('client_invoice, product', 'numerical', 'integerOnly'=>true),
			array('quantity, unit_value', 'numerical','min'=>0),
			array('quantity, unit_value', 'length', 'max'=>11),
			array('notes', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, client_invoice, quantity, product, unit_value, notes', 'safe', 'on'=>'search'),
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
			'clientInvoice' => array(self::BELONGS_TO, 'ClientInvoice', 'client_invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ClientInvoiceDetail.ID'),
			'client_invoice'=>Yii::t('app','model.ClientInvoiceDetail.Client Invoice'),
			'quantity'=>Yii::t('app','model.ClientInvoiceDetail.Quantity'),
			'product'=>Yii::t('app','model.ClientInvoiceDetail.Product'),
			'unit_value'=>Yii::t('app','model.ClientInvoiceDetail.Unit Value'),
			'notes'=>Yii::t('app','model.ClientInvoiceDetail.Notes'),
			'totalValue'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
			'sumTotalValue'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.client_invoice',$this->client_invoice);
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
		return ClientInvoiceDetail::model()->find($criteria);
	}

	public static function getCount($headerId=null) {
		if($headerId) {
			$condition='client_invoice=:id AND quantity > 0';
			$parms=array(':id'=>$headerId);
		}
		return ClientInvoiceDetail::model()->count($condition,$parms);
	}

	public function isEditable() {
		if($this->clientInvoice->status!==Yii::app()->params['ClientInvoice_final_status'])
			return true;
		return false;
	}


	/********PROTECTED CODE FROM HERE************/
}