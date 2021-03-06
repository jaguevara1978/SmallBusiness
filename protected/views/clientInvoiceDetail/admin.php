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
$totalProvider=$model->totalProvider();
$disabled=false;
$disabled=!$model->isEditable();
$productList=Product::getList();
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'client-invoice-detail-grid',
    'type'=>'striped bordered condensed',
	'fixedHeader'=>true,'headerOffset'=>40,
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'class'=>'EditableColumn',
			'name'=>'quantity',
			'footer'=>$totalProvider->sumTotalWeight,
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDetail/updateQuantity'),
					'inputclass'=>'span2',
					'onSave' => 'js: function(e, params) {
									totalValueMultiply(
										params.newValue,
										$(this).parent().parent().children(":nth-child(3)").text(),
										$(this).parent().parent().children(":nth-child(5)")
									);
								}',
					'options'=>array('disabled'=>$disabled,),
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'product',
			'filter'=>$productList,
			'footer'=>ClientInvoiceDetail::getCount($model->client_invoice).' '.Yii::t('app','general.label.Products'),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDetail/updateProduct'),
					
					'type'=>'select',
				'source'=>$productList,
				'options'=>array('showbuttons'=>false,'disabled'=>$disabled,),
					
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'unit_value',
			'type'=>'number',
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDetail/updateUnitValue'),
					'inputclass'=>'span2',
					'onSave' => 'js: function(e, params) {
									totalValueMultiply(
										params.newValue,
										$(this).parent().parent().children(":nth-child(1)").text(),
										$(this).parent().parent().children(":nth-child(5)")
									);
								}',
					'options'=>array('disabled'=>$disabled,),
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'notes',
			
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDetail/updateNotes'),
					'inputclass'=>'span2',
					'type'=>'textarea',
					
					'placement'=>'left',
					'options'=>array('disabled'=>$disabled,),
			),
		),
		array(
			'name'=>'totalValue',
			'value'=>'$data->quantity * $data->unit_value',
			'type'=>'number',
			'footer'=>$totalProvider->sumTotalValue,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'filter'=>false,
		),
		array (
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'header'=>Yii::t('app','general.label.ops'),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.ClientInvoiceDetail.Quantity').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDetail.Product').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDetail.UnitValue').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDetail.Notes').": '+$(this).parent().parent().children(':nth-child(4)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDetail.totalValue').": '+$(this).parent().parent().children(':nth-child(5)').text()",
		),
	),
)); ?>