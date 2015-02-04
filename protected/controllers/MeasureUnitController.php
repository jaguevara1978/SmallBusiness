<?php

class MeasureUnitController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/baseData';

	public function actions() {
		return array(
			'toggle' => array(
				'class'=>'bootstrap.actions.TbToggleAction',
				'modelName' => 'MeasureUnit',
			)
		);
	}

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
		$model=new MeasureUnit;
		$filterModel=new MeasureUnit('search');
		if (intval(Yii::app()->request->getParam('clearFilters'))==1)
			ButtonColumnClearFilters::clearFilters($this,$filterModel);
		
		if(isset($_POST['MeasureUnit'])) {
			$model->attributes=$_POST['MeasureUnit'];
			if($model->save()) {
				if (Yii::app()->request->isAjaxRequest && $asDialog) {
					echo CJSON::encode(array(
							'status'=>'success',
							'div'=>Yii::t('app','message.successFullySaved'),
							'value'=>array(0=>$model->id, 1=>$model->code, 2=>$model->name,),
					));
					exit;
				} else {
					$model=new MeasureUnit;
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

		if(isset($_POST['MeasureUnit'])) {
			$model->attributes=$_POST['MeasureUnit'];
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
		$dataProvider=new CActiveDataProvider('MeasureUnit');
		$this->render('index',array('dataProvider'=>$dataProvider,));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin() {
		$model=new MeasureUnit('search');
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
		$model=MeasureUnit::model()->findByPk($id);
		if($model===null) throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='measure-unit-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionUpdateId() {
		$es = new EditableSaver('MeasureUnit');
		$es->update();
	}

	public function actionUpdateCode() {
		$es = new EditableSaver('MeasureUnit');
		$es->update();
	}

	public function actionUpdateName() {
		$es = new EditableSaver('MeasureUnit');
		$es->update();
	}

	public function actionUpdateEqReference() {
		$es = new EditableSaver('MeasureUnit');
		$es->update();
	}

	public function actionUpdateReference() {
		$es = new EditableSaver('MeasureUnit');
		$es->update();
	}
}
