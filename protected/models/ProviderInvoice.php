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
 * This is the model class for table "provider_invoice".
 *
 * The followings are the available columns in table 'provider_invoice':
 * @property integer $id
 * @property integer $provider
 * @property string $date
 * @property integer $status
 * @property string $payment_date
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Provider $provider0
 * @property Status $status0
 * @property ProviderInvoiceDeposit[] $providerInvoiceDeposits
 * @property ProviderInvoiceDetail[] $providerInvoiceDetails
 */
class ProviderInvoice extends CActiveRecord {
	public $totalValue;
	public $sumTotalValue;
	public $totalDeposits;
	public $sumTotalDeposits;
	public $totalPending;
	public $sumTotalPending;

	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ProviderInvoice_def_id'];
		if (!$this->provider) $this->provider=Yii::app()->params['ProviderInvoice_def_provider'];
		if (!$this->date) $this->date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ProviderInvoice_def_date']));
		if (!$this->status) $this->status=Yii::app()->params['ProviderInvoice_def_status'];
		if (!$this->payment_date) $this->payment_date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ProviderInvoice_def_payment_date']));
		if (!$this->notes) $this->notes=Yii::app()->params['ProviderInvoice_def_notes'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ProviderInvoice_def_id'=>null,
		//'ProviderInvoice_def_provider'=>null,
		//'ProviderInvoice_def_date'=>'Today',
		//'ProviderInvoice_def_status'=>null,
		//'ProviderInvoice_def_payment_date'=>'Today',
		//'ProviderInvoice_def_notes'=>null,
		parent::afterConstruct();
	}

	protected function beforeSave() {
		return parent::beforeSave();
	}

	protected function afterSave() {
		if ($this->isNewRecord){
			if (Yii::app()->params['ProviderInvoice_autoGenerate_details']) {
				$builder=$this->getCommandBuilder();
				$command = $builder->createSqlCommand('CALL load_provider_invoice_details(:id,:date)',array(':id'=>$this->id,':date'=>$this->date));
				$command->execute();
			}
		}

		Stock::createFromPI($this);
		parent::afterSave();
	}

	protected function beforeDelete() {
		Stock::model()->deleteAll('provider_invoice=:id',array(':id'=>$this->id));
		ProviderInvoiceDeposit::model()->deleteAll('provider_invoice=:id',array(':id'=>$this->id));
		ProviderInvoiceDetail::model()->deleteAll('provider_invoice=:id',array(':id'=>$this->id));
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
	 * @return ProviderInvoice the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'provider_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'UnqAttrValidate', 'with'=>'id'),
			array('provider,date,status,payment_date','required'),
			array('id,provider,status','numerical','integerOnly'=>true),
			array('id','length','max'=>11),
			array('provider','length','max'=>5),
			array('status','length','max'=>2),
			array('notes','length','max'=>200),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, provider, date, status, payment_date, notes', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'provider0' => array(self::BELONGS_TO, 'Provider', 'provider'),
			'status0' => array(self::BELONGS_TO, 'Status', 'status'),
			'providerInvoiceDeposits' => array(self::HAS_MANY, 'ProviderInvoiceDeposit', 'provider_invoice'),
			'providerInvoiceDetails' => array(self::HAS_MANY, 'ProviderInvoiceDetail', 'provider_invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ProviderInvoice.ID'),
			'provider'=>Yii::t('app','model.ProviderInvoice.Provider'),
			'date'=>Yii::t('app','model.ProviderInvoice.Date'),
			'status'=>Yii::t('app','model.ProviderInvoice.Status'),
			'payment_date'=>Yii::t('app','model.ProviderInvoice.Payment Date'),
			'notes'=>Yii::t('app','model.ProviderInvoice.Notes'),
			'totalValue'=>Yii::t('app','general.label.totalValue'),
			'sumTotalValue'=>Yii::t('app','general.label.sumTotalValue'),
			'totalDeposits'=>Yii::t('app','general.label.totalDeposits'),
			'sumTotalDeposits'=>Yii::t('app','general.label.sumTotalDeposits'),
			'totalPending'=>Yii::t('app','general.label.totalPending'),
			'sumTotalPending'=>Yii::t('app','general.label.sumTotalPending'),
		);
	}
	
	public function criteria(){
		$criteria=new CDbCriteria;
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.provider',$this->provider);
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.payment_date',$this->payment_date,true);
		$criteria->compare('t.notes',$this->notes,true);
		return $criteria;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=$this->criteria();
		$criteria=$this->criteria();
// 		SELECT SUM(cid.quantity * cid.unit_value)
// 		FROM client_invoice t
// 		JOIN client_invoice_detail cid
// 		ON cid.client_invoice=t.id;
		
// 		SELECT SUM(cid.value)
// 		FROM client_invoice t
// 		JOIN client_invoice_deposit cid
// 		ON cid.client_invoice=t.id;
		
		$fields='t.id,t.provider,t.date,t.status,t.payment_date,t.notes';
		$criteria->select=$fields.'
						,FORMAT(calculate_pinvoice_value(t.id),0) totalValue
						,FORMAT(calculate_pinvoice_deposits(t.id),0) totalDeposits
						,FORMAT(calculate_pinvoice_value(t.id) - calculate_pinvoice_deposits(t.id),0) totalPending';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.date DESC, t.id DESC',),
		));
	}
	
	public function totalProvider() {
		$criteria=$this->criteria();
		$criteria->select='FORMAT(SUM(totals.totalValue),0) sumTotalValue
				,FORMAT(SUM(totals.totalDeposits),0) sumTotalDeposits
				,FORMAT(SUM(totals.totalPending),0) sumTotalPending';
		$internalCondition='';
		if ($criteria->condition) $internalCondition = ' WHERE '.$criteria->condition;
		$criteria->join=',(
						SELECT
							t.id id
							,calculate_pinvoice_value(t.id) totalValue
							,calculate_pinvoice_deposits(t.id) totalDeposits
							,calculate_pinvoice_value(t.id) - calculate_pinvoice_deposits(t.id) totalPending
						FROM provider_invoice t
						'.$internalCondition.'
						) totals';
		$criteria->addCondition('t.id=totals.id');
		return ProviderInvoice::model()->find($criteria);
	}

	public static function getCount() {
		$count = ProviderInvoice::model()->count();
		return $count;
	}

	public function isEditable() {
		return true;
	}


	/********PROTECTED CODE FROM HERE************/
	public static function calculateStatus($id,$date=null) {
		$model=ProviderInvoice::model()->findByPk($id);
		$builder=$model->getCommandBuilder();
		$command = $builder->createSqlCommand('SELECT calculate_pinvoice_deposits(:id)',array(':id'=>$id,));
		$totalDeposits=$command->queryScalar();
		$command = $builder->createSqlCommand('SELECT calculate_pinvoice_value(:id)',array(':id'=>$id,));
		$totalInvoice=$command->queryScalar();
		if ($totalDeposits >= $totalInvoice) {
			$status=Yii::app()->params['ProviderInvoice_final_status'];
			if(!$date) $date=date(Yii::app()->params['General_Date_Format'], strtotime('Today'));
		} else {
			$status=Yii::app()->params['ProviderInvoice_stock_status'];
		}
		$model->status=$status;
		if($date) $model->payment_date=$date;
		return $model->update();
	}

	public static function pay($id) {
		$deposit=new ProviderInvoiceDeposit;
		$builder=$deposit->getCommandBuilder();
		$command=$builder->createSqlCommand('SELECT calculate_pinvoice_value(:id) - calculate_pinvoice_deposits(:id)',array(':id'=>$id,));
		$value=$command->queryScalar();
		if($value>0) {
			$deposit=new ProviderInvoiceDeposit;
			$deposit->provider_invoice=$id;
			$deposit->value=$value;
			$deposit->save();
		}
		ProviderInvoiceDetail::model()->deleteAll('provider_invoice=:id AND (quantity<=0 OR unit_value<=0)',array(':id'=>$id));
	}
}