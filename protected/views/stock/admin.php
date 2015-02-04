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
/* @var $this StockController */
/* @var $model Stock */
$totalProvider=$model->totalProvider();
$reInstall=Utilities::registerDatePickerReInstall(array('date'));
$productList=Product::getList();
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'stock-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate'=>$reInstall,
	'columns'=>array(
		array(
			'class'=>'EditableColumn',
			'name'=>'quantity',
			
			'editable'=>array(
					'url'=>CController::createUrl('Stock/updateQuantity'),
					'inputclass'=>'span2',
					'onSave' => 'js: function(e, params) {
									totalValueMultiply(
										params.newValue,
										$(this).parent().parent().children(":nth-child(3)").text(),
										$(this).parent().parent().children(":nth-child(4)")
									);
									totalValueMultiply(
										params.newValue,
										$(this).parent().parent().children(":nth-child(5)").text(),
										$(this).parent().parent().children(":nth-child(6)")
									);
								}',
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'product',
			'filter'=>$productList,
			'editable'=>array(
					'url'=>CController::createUrl('Stock/updateProduct'),
					
					'type'=>'select',
				'source'=>$productList,
				'options'=>array('showbuttons'=>false,),
					
			),
		),
		'detail_unit_value',
		/*array(
			'class'=>'EditableColumn',
			'name'=>'detail_unit_value',
			'type'=>'number',
		),*/
		array(
			'name'=>'totalValueDetail',
			'value'=>'$data->quantity * $data->detail_unit_value',
			'type'=>'number',
			//'footer'=>$totalProvider->sumTotalValue,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'filter'=>false,
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'manual_unit_value',
			'type'=>'number',
			'editable'=>array(
					'url'=>CController::createUrl('Stock/updateManualUnitValue'),
					'inputclass'=>'span2',
					'onSave' => 'js: function(e, params) {
									totalValueMultiply(
										params.newValue,
										$(this).parent().parent().children(":nth-child(1)").text(),
										$(this).parent().parent().children(":nth-child(6)")
									);
								}',
					'options'=>array('disabled'=>false,),
			),
		),
		array(
			'name'=>'totalValueManual',
			'value'=>'$data->quantity * $data->manual_unit_value',
			'type'=>'number',
			//'footer'=>$totalProvider->sumTotalValueManual,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'filter'=>false,
		),
		Utilities::editableColumnDatePicker($this,$model,'Stock/updateDate','date'),
		array(
			'class'=>'EditableColumn',
			'name'=>'notes',
			
			'editable'=>array(
					'url'=>CController::createUrl('Stock/updateNotes'),
					'inputclass'=>'span2',
					'type'=>'textarea',
					
					'placement'=>'left',
			),
		),
		Utilities::toggleButtonColumn('movement_type','Stock/toggle',Yii::t('app','general.label.stockOutToolTip'),Yii::t('app','general.label.stockInToolTip')),
		array(
	        'name'  => 'client_invoice',
	        'value' => 'CHtml::link($data->client_invoice, Yii::app()->createUrl("ClientInvoiceDetail/create",array("headerId"=>$data->client_invoice)))',
	        'type'  => 'raw',
	    ),
		array(
	        'name'  => 'provider_invoice',
	        'value' => 'CHtml::link($data->provider_invoice, Yii::app()->createUrl("ProviderInvoiceDetail/create",array("headerId"=>$data->provider_invoice)))',
	        'type'  => 'raw',
	    ),
			
		array (
			'class'=>'ButtonColumnClearFilters',
			'template'=>'{delete}',
			'label'=>Yii::t('app','general.button.clearFilters'),
			'header'=>Yii::t('app','general.label.ops'),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.Stock.Quantity').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.Stock.Product').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.Stock.Date').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.Stock.Client Invoice').": '+$(this).parent().parent().children(':nth-child(5)').text()
				+'\\n".Yii::t('app','model.Stock.Provider Invoice').": '+$(this).parent().parent().children(':nth-child(6)').text()",
		),
	),
)); ?>
