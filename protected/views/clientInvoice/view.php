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
/* @var $this ClientInvoiceController */
/* @var $model ClientInvoice */
$this->breadcrumbs=array(
	Yii::t('app','general.label.Client Invoices'),
	$model->id,
);
$this->menu=array(
		array('label'=>Yii::t('app','general.label.Create')." ".Yii::t('app','general.label.ClientInvoice'), 'url'=>array('create')),
		array('label'=>Yii::t('app','general.label.Update')." ".Yii::t('app','general.label.ClientInvoice'), 'url'=>array('/ClientInvoiceDetail/create', 'headerId'=>$model->id)),
);
$title=Yii::t('app','general.label.ClientInvoice').' '.$model->id;
Utilities::printWidget($this,$title,'print_top');
?>
<div id="printable"> 
<?php $this->renderPartial('_view', array('model'=>$model,'print'=>1)); ?>
<?php $this->renderPartial('/clientInvoiceDetail/view',array('headerId'=>$model->id));?>
</br>
<div class="page-header" style="padding-bottom:0px;margin: 40px 0 0;border-bottom:0px solid #eee;">
<table>
<tr>
  <td style="text-align: left">
  ------------------------------------------------ </br>
   Firma
  </td>
</tr>
</table>
</div>
</div>

<?php Utilities::printWidget($this,$title,'print_bottom'); ?>