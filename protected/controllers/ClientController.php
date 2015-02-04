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
class ClientController extends Controller
 {
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
	public function actionCreate($asDialog=false) {
		$model=new Client;
		$filterModel=new Client('search');
		if (intval(Yii::app()->request->getParam('clearFilters'))==1)
			ButtonColumnClearFilters::clearFilters($this,$filterModel);
		
		if(isset($_POST['Client'])) {
			$model->attributes=$_POST['Client'];
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest && $asDialog) {
					echo CJSON::encode(array(
							'status'=>'success',
							'div'=>Yii::t('app','message.successFullySaved'),
							'value'=>array(0=>$model->id, 1=>$model->code, 2=>$model->name,),
					));
					exit;
				} else {
					$model=new Client;
					Yii::app()->user->setFlash('success',Yii::t('app','message.successFullySaved'));
					$this->redirect('create');
				}
			}
		}

		if (Yii::app()->request->isAjaxRequest && $asDialog) {
			echo CJSON::encode(array(
					'status'=>'failure',
					'div'=>$this->renderPartial('_form', array('model'=>$model), true)));
			return;
		} else $this->render('create',array('model'=>$model,'filterModel'=>$filterModel,));
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

		if(isset($_POST['Client'])) {
			$model->attributes=$_POST['Client'];
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
		if(!isset($_GET['ajax'])) $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() {
		$dataProvider=new CActiveDataProvider('Client');
		$this->render('index',array('dataProvider'=>$dataProvider,));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model=new Client('search');
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
		$model=Client::model()->findByPk($id);
		if($model===null) throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='client-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionUpdateId() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdateCode() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdateName() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdateDescription() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdateAddress() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdatePhone() {
		$es = new EditableSaver('Client');
		$es->update();
	}

	public function actionUpdateMobile() {
		$es = new EditableSaver('Client');
		$es->update();
	}
}
