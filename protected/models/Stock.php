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
 * This is the model class for table "stock".
 *
 * The followings are the available columns in table 'stock':
 * @property integer $id
 * @property string $quantity
 * @property integer $product
 * @property string $date
 * @property string $movement_type
 * @property integer $client_invoice
 * @property integer $client_invoice_detail
 * @property integer $provider_invoice_detail
 * @property string $notes
 * @property string $manual_unit_value
 * @property string $detail_unit_value
 *
 * The followings are the available model relations:
 * @property Product $product0
 */
class Stock extends CActiveRecord {
	public $sumTotalValue;
	public $sumTotalValueManual;

	const MOVEMENT_TYPE_OUT='0';
	const MOVEMENT_TYPE_IN='1';
	
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['Stock_def_id'];
		if (!$this->quantity) $this->quantity=Yii::app()->params['Stock_def_quantity'];
		if (!$this->product) $this->product=Yii::app()->params['Stock_def_product'];
		if (!$this->date) $this->date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['Stock_def_date']));
		if (!$this->notes) $this->notes=Yii::app()->params['Stock_def_notes'];
		if (!$this->movement_type) $this->movement_type=Yii::app()->params['Stock_def_movement_type'];
		if (!$this->client_invoice_detail) $this->client_invoice_detail=Yii::app()->params['Stock_def_client_invoice_detail'];
		if (!$this->provider_invoice_detail) $this->provider_invoice_detail=Yii::app()->params['Stock_def_provider_invoice_detail'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'Stock_def_id'=>null,
		//'Stock_def_quantity'=>null,
		//'Stock_def_product'=>null,
		//'Stock_def_date'=>'Today',
		//'Stock_def_notes'=>null,
		//'Stock_def_movement_type'=>null,
		//'Stock_def_client_invoice_detail'=>null,
		//'Stock_def_provider_invoice_detail'=>null,
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
	 * @return Stock the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('quantity,product,date,,movement_type','required'),
			array('id,product,client_invoice_detail,provider_invoice_detail','numerical','integerOnly'=>true),
			array('quantity,manual_unit_value,detail_unit_value','numerical','min'=>0),
			array('id,client_invoice_detail,provider_invoice_detail,detail_unit_value,manual_unit_value','length','max'=>11),
			array('quantity','length','max'=>8),
			array('product','length','max'=>3),
			array('notes','length','max'=>100),
			array('movement_type','length','max'=>1),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, quantity, product, date, movement_type, client_invoice, provider_invoice
					, client_invoice_detail, provider_invoice_detail,notes', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.Stock.ID'),
			'quantity'=>Yii::t('app','model.Stock.Quantity'),
			'product'=>Yii::t('app','model.Stock.Product'),
			'date'=>Yii::t('app','model.Stock.Date'),
			'notes'=>Yii::t('app','model.Stock.Notes'),
			'movement_type'=>Yii::t('app','model.Stock.Movement Type'),
			'client_invoice'=>Yii::t('app','model.Stock.Client Invoice'),
			'provider_invoice'=>Yii::t('app','model.Stock.Provider Invoice'),
			'client_invoice_detail'=>Yii::t('app','model.Stock.Client Invoice Detail'),
			'provider_invoice_detail'=>Yii::t('app','model.Stock.Provider Invoice Detail'),
			'manual_unit_value'=>Yii::t('app','model.ClientInvoiceDetail.Unit Value').' Manual',
			'detail_unit_value'=>Yii::t('app','model.ClientInvoiceDetail.Unit Value'),
			'totalValueManual'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
			'totalValueDetail'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
			'sumTotalValue'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
			'sumTotalValueManual'=>Yii::t('app','model.ClientInvoiceDetail.totalValue'),
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.quantity',$this->quantity,true);
		$criteria->compare('t.product',$this->product);
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.movement_type',$this->movement_type,true);
		$criteria->compare('t.client_invoice',$this->client_invoice,true);
		$criteria->compare('t.provider_invoice',$this->provider_invoice,true);
		$criteria->compare('t.client_invoice_detail',$this->client_invoice_detail);
		$criteria->compare('t.provider_invoice_detail',$this->provider_invoice_detail);
		$criteria->compare('t.manual_unit_value',$this->manual_unit_value,true);
		$criteria->compare('t.detail_unit_value',$this->detail_unit_value,true);
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
		return CHtml::listData(Stock::model()->findAll($criteria),'id','name');
	}

	public static function getCount() {
		$count = Stock::model()->count();
		return $count;
	}

	public static function createFromCI($model) {
		if($model->status==Yii::app()->params['ClientInvoice_stock_status']
			|| $model->status==Yii::app()->params['ClientInvoice_final_status']) {
			if(Yii::app()->params['Stock_auto_date']) $date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['Stock_def_date']));
			else $date=$model->date;
			$builder=Stock::model()->getCommandBuilder();
			$command = $builder->createSqlCommand('CALL load_stock_ci(:id,:date)',array(':id'=>$model->id,':date'=>$date));
			$command->execute();
		} else Stock::model()->deleteAll('client_invoice=:id',array(':id'=>$model->id));
	}

	public static function createFromCID($fromModel) {
		if (Stock::model()->count('client_invoice_detail=:id',array(':id'=>$fromModel->id)) > 0) {
			Stock::model()->updateAll(
				array(
					'quantity'=>$fromModel->quantity
					,'product'=>$fromModel->product
					,'detail_unit_value'=>$fromModel->unit_value
				)
				,'client_invoice_detail=:id'
				,array(':id'=>$fromModel->id)
			);
		} else {
			$stock=new Stock;
			$stock->quantity=$fromModel->quantity;
			$stock->product=$fromModel->product;
			$stock->date=$fromModel->clientInvoice->date;
			$stock->movement_type=Stock::MOVEMENT_TYPE_OUT;
			$stock->client_invoice=$fromModel->client_invoice;
			$stock->client_invoice_detail=$fromModel->id;
			$stock->manual_unit_value=$fromModel->unit_value;
			$stock->detail_unit_value=$fromModel->unit_value;
			return $stock->save();
		}
	}

	public static function deleteFromCID($fromModel) {
		return Stock::model()->deleteAll('client_invoice_detail=:id',array(':id'=>$fromModel->id)) > 0;
	}

	public static function createFromPI($model) {
		if($model->status==Yii::app()->params['ProviderInvoice_stock_status']
			|| $model->status==Yii::app()->params['ProviderInvoice_final_status']) {
			if(Yii::app()->params['Stock_auto_date']) $date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['Stock_def_date']));
			else $date=$model->date;
			$builder=Stock::model()->getCommandBuilder();
			$command = $builder->createSqlCommand('CALL load_stock_pi(:id,:date)',array(':id'=>$model->id,':date'=>$date));
			$command->execute();
		} else Stock::model()->deleteAll('provider_invoice=:id',array(':id'=>$model->id));
	}

	public static function createFromPID($fromModel) {
		if (Stock::model()->count('provider_invoice_detail=:id',array(':id'=>$fromModel->id)) > 0) {
			Stock::model()->updateAll(
				array(
					'quantity'=>$fromModel->quantity
					,'product'=>$fromModel->product
					,'detail_unit_value'=>$fromModel->unit_value
				)
				,'provider_invoice_detail=:id'
				,array(':id'=>$fromModel->id)
			);
		} else {
			$stock=new Stock;
			$stock->quantity=$fromModel->quantity;
			$stock->product=$fromModel->product;
			$stock->date=$fromModel->providerInvoice->date;
			$stock->movement_type=Stock::MOVEMENT_TYPE_IN;
			$stock->provider_invoice=$fromModel->provider_invoice;
			$stock->provider_invoice_detail=$fromModel->id;
			$stock->manual_unit_value=$fromModel->unit_value;
			$stock->detail_unit_value=$fromModel->unit_value;
			return $stock->save();
		}
	}

	public static function deleteFromPID($fromModel) {
		return Stock::model()->deleteAll('provider_invoice_detail=:id',array(':id'=>$fromModel->id)) > 0;
	}
	
		
	public function totalProvider() {
		$criteria=$this->criteria();
		$criteria->select="FORMAT(COALESCE(SUM(t.quantity * t.detail_unit_value),0),0) sumTotalValue,
						   FORMAT(COALESCE(SUM(t.quantity * t.manual_unit_value),0),0) sumTotalValueManual";
		/*$criteria->join = 'INNER JOIN product p
							ON p.id = t.product
							INNER JOIN measure_unit mu
							ON mu.id = p.measure_unit';*/
		return Stock::model()->find($criteria);
	}

}