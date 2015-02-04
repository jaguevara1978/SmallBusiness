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
/* @var $this ProviderInvoiceController */
/* @var $model ProviderInvoice */
/* @var $css */
if(!$print) Utilities::showHideLink('ProviderInvoice',Yii::t('app',  'general.label.ProviderInvoice'),Yii::app()->params['General_DefaultShowHeaders']);
if(!$css) $css=Utilities::getCustomCSSPath();
$totals=$model->totalProvider();
$this->widget('zii.widgets.CDetailView', array(
		'id' => 'ProviderInvoice',
		'data'=>$model,
		'cssFile' => $css,
		'htmlOptions'=>array(
			'class'=>'editableDetail table table-bordered table-striped table-hover',
			'style'=>Yii::app()->params['General_DefaultShowHeaders'] || $print ?'':'display:none;'
		),
		'nullDisplay'=>'',
		'attributes'=>array(
			'id',
			array('name'=>'provider0.name',),
			'date',
			array('name'=>'status0.name',),
			'payment_date',
			'notes',
			array(
				'name'=>Yii::t('app','general.label.totals'),
				'value'=>Yii::t('app','model.ProviderInvoice.totalValue').': '.$totals->sumTotalValue
						.' - '.Yii::t('app','model.ProviderInvoice.totalDeposits').': '.$totals->sumTotalDeposits
						.' - '.Yii::t('app','model.ProviderInvoice.totalPending').': '.$totals->sumTotalPending,
			),
		),
));
?>