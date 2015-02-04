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
/* @var $this ClientInvoiceDepositController */
/* @var $model ClientInvoiceDeposit */
/* @var $headerId */
if(!$model) {
	$model= new ClientInvoiceDeposit('search');
	$model->client_invoice=$headerId;
}
$totalProvider=$model->totalProvider();
$this->widget('bootstrap.widgets.TbGridView', array(
	'dataProvider'=>$model->search(false),
	'id'=>'ClientInvoiceDeposit',
	'itemsCssClass'=>'print',
	'cssFile'=>Yii::app()->params['General_CustomCSS'],
	'summaryText'=>'',
	'enableSorting'=>false,
	'columns'=>array(
		'date',
		'value',
		'notes',
		'final_payment',
		array(
			'name'=>'totalValue',
			'value'=>'',
			'type'=>'number',
			'footer'=>$totalProvider->sumTotalValue,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
		),
	),
));
?>