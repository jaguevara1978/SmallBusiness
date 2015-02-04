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
/* @var $css */
if(!$print) Utilities::showHideLink('ClientInvoice',Yii::t('app',  'general.label.ClientInvoice'),Yii::app()->params['General_DefaultShowHeaders']);
if(!$css) 
	if (!$print) 
		$css=Utilities::getCustomCSSPath();
	else
		$css=Utilities::getPrintCSS('ClientInvoice');

$totals=$model->totalProvider();
$clientName=$model->client0->name;
if($model->client0->phone) $clientName.=' - '.Yii::t('app','model.Client.Phone').': '.$model->client0->phone;
if($model->client0->mobile) $clientName.=' - '.Yii::t('app','model.Client.Mobile').': '.$model->client0->mobile;
?>

<div class="page-header" style="padding-bottom:0px;margin: 0px 0 0;border-bottom:0px solid #eee;">
<table style="margin-bottom: 0px;">
<tr>
  <td style="padding:0;">
  	<h4 style="margin: 0;"><?php echo (Yii::app()->params['General_appName']) ?><small> Remisión No. <span class="badge"><?php echo $model->id ?></span></small></h4>
  	<p style="margin:0;">Tel.: 511 76 72</br>
  	Medellín - Colombia</p>
  </td>
  <td style="text-align: right;padding:0;">
  <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/logo.png'
  		,'Test'
  		,array('width'=>'160px','height'=>'80px','title'=>Yii::app()->params['General_appName'])) 
  ?>
  </td>
</tr>
</table>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
	'id'=>'ClientInvoice',
	'data'=>$model,
	'cssFile'=>$css,
	'htmlOptions'=>array(
		'class'=>'editableDetail table table-bordered table-striped table-hover',
		'style'=>Yii::app()->params['General_DefaultShowHeaders'] || $print ?'':'display:none;'
	),
	'nullDisplay'=>'',
	'attributes'=>array(
		array('name'=>'client','value'=>$clientName),
		array('name'=>'','value'=>'Medellín - Antioquia'),
		'date',
// 		array('name'=>'status0.name',),
// 		'payment_date',
// 		'notes',
// 		array(
// 			'name'=>Yii::t('app','general.label.totals'),
// 			'value'=>Yii::t('app','model.ClientInvoice.totalValue').': '.$totals->sumTotalValue
// 					.' - '.Yii::t('app','model.ClientInvoice.totalDeposits').': '.$totals->sumTotalDeposits
// 					.' - '.Yii::t('app','model.ClientInvoice.totalPending').': '.$totals->sumTotalPending,
// 		),
	),
));
?>