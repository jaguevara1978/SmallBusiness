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
class ClientInvoiceDepositController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/baseData';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->render('view',array('model'=>$this->loadModel($id),));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($headerId=null) {
		$model=new ClientInvoiceDeposit;
		$filterModel=new ClientInvoiceDeposit('search');
		if (intval(Yii::app()->request->getParam('clearFilters'))==1)
			ButtonColumnClearFilters::clearFilters($this,$filterModel);
		
		if(isset($_POST['ClientInvoiceDeposit'])) {
			$model->attributes=$_POST['ClientInvoiceDeposit'];
			if($model->save()) {
				$model=new ClientInvoiceDeposit;
				Yii::app()->user->setFlash('success',Yii::t('app','message.successFullySaved'));
				$this->redirect(array('create','headerId'=>$headerId));
			}
		}
		$model->client_invoice=$headerId;
		$filterModel->client_invoice=$headerId;
		$this->render('create',array('model'=>$model,'filterModel'=>$filterModel,));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ClientInvoiceDeposit'])) {
			$model->attributes=$_POST['ClientInvoiceDeposit'];
			if($model->save()) $this->redirect(array('admin'));
		}

		$this->render('update',array('model'=>$model,));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) {
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('create','headerId'=>$model->client_invoice));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$dataProvider=new CActiveDataProvider('ClientInvoiceDeposit');
		$this->render('index',array('dataProvider'=>$dataProvider,));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model=new ClientInvoiceDeposit('search');
		if (intval(Yii::app()->request->getParam('clearFilters'))==1)
			ButtonColumnClearFilters::clearFilters($this,$model);
		$this->render('admin',array('model'=>$model,));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model=ClientInvoiceDeposit::model()->findByPk($id);
		if($model===null) throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='client-invoice-deposit-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionUpdateId() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
	}

	public function actionUpdateClientInvoice() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
	}

	public function actionUpdateDate() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
		$model=ClientInvoiceDeposit::model()->findByPk($es->primaryKey);
		if ($model->final_payment=='1') {
			ClientInvoice::model()->updateByPk($model->client_invoice, array('payment_date'=>$es->value));
			ClientInvoiceDeposit::model()->updateAll(array('date'=>$es->value),'final_payment=1 AND client_invoice=:id',
				array(':id'=>ClientInvoiceDeposit::model()->findByPk($es->primaryKey)->client_invoice));
		}
	}

	public function actionUpdateValue() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
	}

	public function actionUpdateNotes() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
	}

	public function actionUpdateFinalPayment() {
		$es = new EditableSaver('ClientInvoiceDeposit');
		$es->update();
	}

	public function actions() {
		return array(
			'toggle' => array(
				'class'=>'bootstrap.actions.TbToggleAction',
				'modelName'=>'ClientInvoiceDeposit',
			)
		);
	}
}
