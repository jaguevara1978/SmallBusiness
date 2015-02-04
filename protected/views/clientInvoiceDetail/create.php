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
/* @var $this ClientInvoiceDetailController */
/* @var $model ClientInvoiceDetail */
/* @var $filterModel ClientInvoiceDetail */

$this->breadcrumbs=array(
	Yii::t('app','general.label.Client Invoice Details'),
	Yii::t('app','general.label.Create'),
);
$this->menu=array(
	array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.ClientInvoice'), 'url'=>array('/ClientInvoice/create')),
	array('label'=>Yii::t('app','general.label.Print')." ".Yii::t('app','general.label.ClientInvoice'), 'url'=>array('/ClientInvoice/view','id'=>$model->client_invoice)),
	array('label'=>Yii::t('app','general.label.Dispatch'), 'url'=>array('/ClientInvoice/loadStock','id'=>$model->client_invoice)),
);
?>

<?php
	if (Yii::app()->user->checkAccess('ClientInvoiceDetail.Create')
		|| Yii::app()->user->checkAccess('ClientInvoiceDetail.*')) 
		echo $this->renderPartial('_form', array('model'=>$model)); 
?>

<?php 
	if (Yii::app()->user->checkAccess('ClientInvoiceDetail.Admin')
		|| Yii::app()->user->checkAccess('ClientInvoiceDetail.*')) 
		echo $this->renderPartial('admin', array('model'=>$filterModel));
?>