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
/* @var $this ProviderInvoiceDepositController */
/* @var $model ProviderInvoiceDeposit */
/* @var $filterModel ProviderInvoiceDeposit */

$this->breadcrumbs=array(
	Yii::t('app','general.label.Provider Invoice Deposits'),
	Yii::t('app','general.label.Create'),
);
$this->menu=array(
	array('label'=>Yii::t('app','general.label.Create').' '.Yii::t('app','general.label.ProviderInvoice'), 'url'=>array('/ProviderInvoice/create')),
	array('label'=>Yii::t('app','general.label.Print').' '.Yii::t('app','general.label.ProviderInvoice'), 'url'=>array('/ProviderInvoice/view','id'=>$model->provider_invoice)),
);
?>

<?php
	if (Yii::app()->user->checkAccess('ProviderInvoiceDeposit.Create')
		|| Yii::app()->user->checkAccess('ProviderInvoiceDeposit.*')) 
		echo $this->renderPartial('_form', array('model'=>$model)); 
?>

<?php 
	if (Yii::app()->user->checkAccess('ProviderInvoiceDeposit.Admin')
		|| Yii::app()->user->checkAccess('ProviderInvoiceDeposit.*')) 
		echo $this->renderPartial('admin', array('model'=>$filterModel));
?>