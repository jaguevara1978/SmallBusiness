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
 * This is the model class for table "provider_invoice_deposit".
 *
 * The followings are the available columns in table 'provider_invoice_deposit':
 * @property integer $id
 * @property integer $provider_invoice
 * @property string $date
 * @property string $value
 * @property string $notes
 * @property string $final_payment
 *
 * The followings are the available model relations:
 * @property ProviderInvoice $providerInvoice
 */
class ProviderInvoiceDeposit extends CActiveRecord {
	public $sumTotalValue;

	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ProviderInvoiceDeposit_def_id'];
		if (!$this->provider_invoice) $this->provider_invoice=Yii::app()->params['ProviderInvoiceDeposit_def_provider_invoice'];
		if (!$this->date) $this->date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ProviderInvoiceDeposit_def_date']));
		if (!$this->value) $this->value=Yii::app()->params['ProviderInvoiceDeposit_def_value'];
		if (!$this->notes) $this->notes=Yii::app()->params['ProviderInvoiceDeposit_def_notes'];
		if (!$this->final_payment) $this->final_payment=Yii::app()->params['ProviderInvoiceDeposit_def_final_payment'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ProviderInvoiceDeposit_def_id'=>null,
		//'ProviderInvoiceDeposit_def_provider_invoice'=>null,
		//'ProviderInvoiceDeposit_def_date'=>'Today',
		//'ProviderInvoiceDeposit_def_value'=>null,
		//'ProviderInvoiceDeposit_def_notes'=>null,
		//'ProviderInvoiceDeposit_def_final_payment'=>null,
		parent::afterConstruct();
	}

	protected function beforeSave() {
		if($this->isNewRecord) {
			$builder=$this->getCommandBuilder();
			$command = $builder->createSqlCommand('SELECT calculate_pinvoice_value(:id) - calculate_pinvoice_deposits(:id)',array(':id'=>$this->provider_invoice,));
			$pendingValue=$command->queryScalar();
			if ($this->value >= $pendingValue) $this->final_payment='1';
		}
		return parent::beforeSave();
	}

	protected function afterSave() {
		ProviderInvoice::model()->calculateStatus($this->provider_invoice,$this->date);
		parent::afterSave();
	}

	protected function beforeDelete() {
		return parent::beforeSave();
	}

	protected function afterDelete() {
		ProviderInvoice::model()->calculateStatus($this->provider_invoice);
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
	 * @return ProviderInvoiceDeposit the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'provider_invoice_deposit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('provider_invoice,date,value','required'),
			array('id,provider_invoice','numerical','integerOnly'=>true),
			array('value','numerical','min'=>0),
			array('id,provider_invoice,value','length','max'=>11),
			array('notes','length','max'=>80),
			array('final_payment','length','max'=>1),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, provider_invoice, date, value, notes, final_payment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'providerInvoice' => array(self::BELONGS_TO, 'ProviderInvoice', 'provider_invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ProviderInvoiceDeposit.ID'),
			'provider_invoice'=>Yii::t('app','model.ProviderInvoiceDeposit.Provider Invoice'),
			'date'=>Yii::t('app','model.ProviderInvoiceDeposit.Date'),
			'value'=>Yii::t('app','model.ProviderInvoiceDeposit.Value'),
			'notes'=>Yii::t('app','model.ProviderInvoiceDeposit.Notes'),
			'final_payment'=>Yii::t('app','model.ProviderInvoiceDeposit.Final Payment'),
			'totalValue'=>Yii::t('app','model.ProviderInvoiceDeposit.totalValue'),
			'sumTotalValue'=>Yii::t('app','model.ProviderInvoiceDeposit.sumTotalValue'),
		);
	}
	
	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.provider_invoice',$this->provider_invoice);
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.value',$this->value,true);
		$criteria->compare('t.notes',$this->notes,true);
		$criteria->compare('t.final_payment',$this->final_payment,true);
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
		//Please modify the next line at your will
		$criteria->select='FORMAT(COALESCE(SUM(0),0),0) sumTotalValue';
		return ProviderInvoiceDeposit::model()->find($criteria);
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