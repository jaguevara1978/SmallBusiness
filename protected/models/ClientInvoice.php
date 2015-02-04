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
 * This is the model class for table "client_invoice".
 *
 * The followings are the available columns in table 'client_invoice':
 * @property integer $id
 * @property integer $client
 * @property string $date
 * @property integer $status
 * @property string $payment_date
 * @property string $notes
 * @property string $totalValue
 *
 * The followings are the available model relations:
 * @property Status $status0
 * @property Client $client0
 * @property ClientInvoiceDeposit[] $clientInvoiceDeposits
 * @property ClientInvoiceDetail[] $clientInvoiceDetails
 */
class ClientInvoice extends CActiveRecord {
	public $totalValue;
	public $sumTotalValue;
	public $totalDeposits;
	public $sumTotalDeposits;
	public $totalPending;
	public $sumTotalPending;
	
	protected function afterConstruct() {
		if (!$this->id) $this->id=Yii::app()->params['ClientInvoice_def_id'];
		if (!$this->client) $this->client=Yii::app()->params['ClientInvoice_def_client'];
		if (!$this->date) $this->date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ClientInvoice_def_date']));
		if (!$this->status) $this->status=Yii::app()->params['ClientInvoice_def_status'];
		if (!$this->payment_date) $this->payment_date=date(Yii::app()->params['General_Date_Format'], strtotime(Yii::app()->params['ClientInvoice_def_payment_date']));
		if (!$this->notes) $this->notes=Yii::app()->params['ClientInvoice_def_notes'];
		/*Copy this values at the end of config/main.php, into 'params'=>array()...And replace nulls by the values you want*/
		//'ClientInvoice_def_id'=>null,
		//'ClientInvoice_def_client'=>null,
		//'ClientInvoice_def_date'=>'Today',
		//'ClientInvoice_def_status'=>null,
		//'ClientInvoice_def_payment_date'=>'Today',
		//'ClientInvoice_def_notes'=>null,
		parent::afterConstruct();
	}

	protected function beforeSave() {
		return parent::beforeSave();
	}
	
	protected function afterSave() {
		if ($this->isNewRecord){
			if (Yii::app()->params['ClientInvoice_autoGenerate_details']) {
				$builder=$this->getCommandBuilder();
				$command = $builder->createSqlCommand('CALL load_client_invoice_details(:id,:date)',array(':id'=>$this->id,':date'=>$this->date));
				$command->execute();
			}
		}

		Stock::createFromCI($this);
		parent::afterSave();
	}

	protected function beforeDelete(){
		Stock::model()->deleteAll('client_invoice=:id',array(':id'=>$this->id));
		ClientInvoiceDetail::model()->deleteAll('client_invoice=:id',array(':id'=>$this->id));
		ClientInvoiceDeposit::model()->deleteAll('client_invoice=:id',array(':id'=>$this->id));
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
				'defaultStickOnClear'=>false, /* optional line */
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientInvoice the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'client_invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client, date, status, payment_date', 'required'),
			array('client, status', 'numerical', 'integerOnly'=>true),
			array('notes', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, client, date, status, payment_date, notes, totalValue', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'status0' => array(self::BELONGS_TO, 'Status', 'status'),
			'client0' => array(self::BELONGS_TO, 'Client', 'client'),
			'clientInvoiceDeposits' => array(self::HAS_MANY, 'ClientInvoiceDeposit', 'invoice'),
			'clientInvoiceDetails' => array(self::HAS_MANY, 'ClientInvoiceDetail', 'invoice'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>Yii::t('app','model.ClientInvoice.ID'),
			'client'=>Yii::t('app','model.ClientInvoice.Client'),
			'date'=>Yii::t('app','model.ClientInvoice.Date'),
			'status'=>Yii::t('app','model.ClientInvoice.Status'),
			'payment_date'=>Yii::t('app','model.ClientInvoice.Payment Date'),
			'notes'=>Yii::t('app','model.ClientInvoice.Notes'),
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
		$criteria->compare('t.client',$this->client);
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
// 		SELECT SUM(cid.quantity * cid.unit_value)
// 		FROM client_invoice t
// 		JOIN client_invoice_detail cid
// 		ON cid.client_invoice=t.id;
		
// 		SELECT SUM(cid.value)
// 		FROM client_invoice t
// 		JOIN client_invoice_deposit cid
// 		ON cid.client_invoice=t.id;
		
		$fields='t.id,t.client,t.date,t.status,t.payment_date,t.notes';
		$criteria->select=$fields.'
						,FORMAT(calculate_cinvoice_value(t.id),0) totalValue
						,FORMAT(calculate_cinvoice_deposits(t.id),0) totalDeposits
						,FORMAT(calculate_cinvoice_value(t.id) - calculate_cinvoice_deposits(t.id),0) totalPending';
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
							,calculate_cinvoice_value(t.id) totalValue
							,calculate_cinvoice_deposits(t.id) totalDeposits
							,calculate_cinvoice_value(t.id) - calculate_cinvoice_deposits(t.id) totalPending
						FROM client_invoice t
						'.$internalCondition.'
						) totals';
		$criteria->addCondition('t.id=totals.id');
		return ClientInvoice::model()->find($criteria);
	}

	public static function getCount() {
		$count = ClientInvoice::model()->count();
		return $count;
	}

	public function isEditable() {
		return true;
	}


	/********PROTECTED CODE FROM HERE************/
	public static function calculateStatus($id,$date=null) {
		$model=ClientInvoice::model()->findByPk($id);
		$builder=$model->getCommandBuilder();
		$command = $builder->createSqlCommand('SELECT calculate_cinvoice_deposits(:id)',array(':id'=>$id,));
		$totalDeposits=$command->queryScalar();
		$command = $builder->createSqlCommand('SELECT calculate_cinvoice_value(:id)',array(':id'=>$id,));
		$totalInvoice=$command->queryScalar();
		if ($totalDeposits >= $totalInvoice) {
			$status=Yii::app()->params['ClientInvoice_final_status'];
			if(!$date) $date=date(Yii::app()->params['General_Date_Format'], strtotime('Today'));
		} else {
			$status=Yii::app()->params['ClientInvoice_stock_status'];
		}
		$model->status=$status;
		if($date) $model->payment_date=$date;
		return $model->update();
	}

	public static function pay($id) {
		$deposit=new ClientInvoiceDeposit;
		$builder=$deposit->getCommandBuilder();
		$command=$builder->createSqlCommand('SELECT calculate_cinvoice_value(:id) - calculate_cinvoice_deposits(:id)',array(':id'=>$id,));
		$value=$command->queryScalar();
		if($value>0) {
			$deposit=new ClientInvoiceDeposit;
			$deposit->client_invoice=$id;
			$deposit->value=$value;
			$deposit->save();
		}
		ClientInvoiceDetail::model()->deleteAll('client_invoice=:id AND (quantity<=0 OR unit_value<=0)',array(':id'=>$id));
	}
}