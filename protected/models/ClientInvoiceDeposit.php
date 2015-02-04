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
 * This is the model class for table "client_invoice_deposit".
 *
 * The followings are the available columns in table 'client_invoice_deposit':
 * @property integer $id
 * @property integer $client_invoice
 * @property string $date
 * @property string $value
 * @property string $notes
 * @property string $final_payment
 *
 * The followings are the available model relations:
 * @property ClientInvoice $clientInvoice
 */
class ClientInvoiceDeposit extends CActiveRecord {
	public $sumTotalValue;

	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ClientInvoiceDeposit_def_id'];
		if (!$this->client_invoice) $this->client_invoice=Yii::app()->params['ClientInvoiceDeposit_def_client_invoice'];
		if (!$this->date) $this->date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ClientInvoiceDeposit_def_date']));
		if (!$this->value) $this->value=Yii::app()->params['ClientInvoiceDeposit_def_value'];
		if (!$this->notes) $this->notes=Yii::app()->params['ClientInvoiceDeposit_def_notes'];
		if (!$this->final_payment) $this->final_payment=Yii::app()->params['ClientInvoiceDeposit_def_final_payment'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ClientInvoiceDeposit_def_id'=>null,
		//'ClientInvoiceDeposit_def_client_invoice'=>null,
		//'ClientInvoiceDeposit_def_date'=>'Today',
		//'ClientInvoiceDeposit_def_value'=>null,
		//'ClientInvoiceDeposit_def_notes'=>null,
		//'ClientInvoiceDeposit_def_final_payment'=>null,
		parent::afterConstruct();
	}

	protected function beforeSave() {
		if($this->isNewRecord) {
			$builder=$this->getCommandBuilder();
			$command = $builder->createSqlCommand('SELECT calculate_cinvoice_value(:id) - calculate_cinvoice_deposits(:id)',array(':id'=>$this->client_invoice,));
			$pendingValue=$command->queryScalar();
			if ($this->value >= $pendingValue) $this->final_payment='1';
		}
		return parent::beforeSave();
	}
	
	public function afterSave() {
		ClientInvoice::model()->calculateStatus($this->client_invoice,$this->date);
		parent::afterSave();
	}

	protected function beforeDelete() {
		return parent::beforeSave();
	}

	public function afterDelete() {
		ClientInvoice::model()->calculateStatus($this->client_invoice);
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
	 * @return ClientInvoiceDeposit the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'client_invoice_deposit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_invoice, date, value', 'required'),
			array('client_invoice', 'numerical', 'integerOnly'=>true),
			array('value', 'length', 'max'=>11),
			array('notes', 'length', 'max'=>80),
			array('final_payment', 'length', 'max'=>1),
			array('value', 'numerical'),
				
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, client_invoice, date, value, notes, final_payment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clientInvoice' => array(self::BELONGS_TO, 'ClientInvoice', 'client_invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ClientInvoiceDeposit.ID'),
			'client_invoice'=>Yii::t('app','model.ClientInvoiceDeposit.Client Invoice'),
			'date'=>Yii::t('app','model.ClientInvoiceDeposit.Date'),
			'value'=>Yii::t('app','model.ClientInvoiceDeposit.Value'),
			'notes'=>Yii::t('app','model.ClientInvoiceDeposit.Notes'),
			'final_payment'=>Yii::t('app','model.ClientInvoiceDeposit.Final Payment'),
			'sumTotalValue'=>Yii::t('app','model.ClientInvoiceDeposit.sumTotalValue'),
		);
	}

	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('client_invoice',$this->client_invoice);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('final_payment',$this->final_payment,true);
		return $criteria;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($showQtyZero=true) {
		$criteria=$this->criteria();
		if(!$showQtyZero) $criteria->addCondition('quantity > 0');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'id DESC',),
		));
	}
	
	public function totalProvider() {
		$criteria=$this->criteria();
		$criteria->select='FORMAT(COALESCE(SUM(t.value),0),0) sumTotalValue';
		return ClientInvoiceDeposit::model()->find($criteria);
	}

	public static function getCount($headerId=null) {
		if($headerId) {
			$condition='parent_table=:id AND quantity > 0';
			$parms=array(':id'=>$headerId);
		}
		return ProviderInvoiceDeposit::model()->count($condition,$parms);
	}

	public function isEditable() {
		return true;
	}


	/********PROTECTED CODE FROM HERE************/
}