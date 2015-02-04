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
?>
<?php
/* @var $this ClientInvoiceController */
/* @var $model ClientInvoice */
?>
<?php
$totalProvider=$model->totalProvider();
$reInstall=Utilities::registerDatePickerReInstall(array('date','payment_date'));
$clientList=Client::getList();
$statusList=Status::getList('CLI');
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'client-invoice-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate'=>$reInstall,
	'columns'=>array(
		array('name'=>'id','header'=>'',),
		array(
			'class'=>'EditableColumn',
			'name'=>'client',
			'filter'=>$clientList,
			'editable'=>array(
				'url'=>CController::createUrl('ClientInvoice/updateClient'),

				'type'=>'select',
				'source'=>$clientList,
				'options'=>array('showbuttons'=>false,),
					
			),
		),
		Utilities::editableColumnDatePicker($this,$model,'ClientInvoice/updateDate','date'),
		array(
			'class'=>'EditableColumn',
			'name'=>'status',
			'filter'=>$statusList,
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoice/updateStatus'),
					
					'type'=>'select',
				'source'=>$statusList,
				'options'=>array(
					'display'=>'js: function(value, sourceData) {
													var selected = $.grep(sourceData, function(o){ return value == o.value; }),
													colors = {10: "green", 20: "red", 30: "purple"};
													$(this).text(selected[0].text).css("color", colors[value]);
												}',
					'success'=>'js:function(response, newValue) {$.fn.yiiGridView.update("client-invoice-grid");}',
					'showbuttons'=>false,
				),
					
			),
		),
		Utilities::editableColumnDatePicker($this,$model,'ClientInvoice/updatePaymentDate','payment_date'),
		array(
			'class'=>'EditableColumn',
			'name'=>'notes',
			
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoice/updateNotes'),
					'inputclass'=>'span2',
					'type'=>'textarea',
					
					'placement'=>'left',
			),
		),
		array(
			'name'=>'totalValue',
			'footer'=>$totalProvider->sumTotalValue,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'filter'=>false,
		),
		array(
			'name'=>'totalDeposits',
			'footer'=>$totalProvider->sumTotalDeposits,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_DepositsColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_DepositsFooter']),
			'filter'=>false,
		),
		array(
			'name'=>'totalPending',
			'footer'=>$totalProvider->sumTotalPending,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_PendingColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_PendingFooter']),
			'filter'=>false,
		),
		array (
			'class'=>'ButtonColumnClearFilters',
			'template'=>'{pay}{deposits}{view}{update}{delete}',
			'label'=>Yii::t('app','general.button.clearFilters'),
			'header'=>Yii::t('app','general.label.ops'),
			'htmlOptions'=>array('style'=>'width: 160px;text-align: right;',),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.ClientInvoice.Id').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.Client').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.Date').": '+$(this).parent().parent().children(':nth-child(3)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.Status').": '+$(this).parent().parent().children(':nth-child(4)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.PaymentDate').": '+$(this).parent().parent().children(':nth-child(5)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.Notes').": '+$(this).parent().parent().children(':nth-child(6)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.totalValue').": '+$(this).parent().parent().children(':nth-child(7)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.totalDeposits').": '+$(this).parent().parent().children(':nth-child(8)').text()
				+'\\n".Yii::t('app','model.ClientInvoice.totalPending').": '+$(this).parent().parent().children(':nth-child(9)').text()",
			'buttons'=>array (
				'pay'=>array(
					'url'=>'$this->grid->controller->createUrl("/ClientInvoice/PayInvoice", array("id"=>$data->id))',
     				'visible'=>'($data->status<30 && Yii::app()->user->checkAccess("ClientInvoice.PayInvoice"))?true:false;',
					'imageUrl'=>Yii::app()->params['General_BaseImageDirectory'].'Payment.png',
					'label'=>Yii::t('app','general.label.Pay'),
     				'click'=>"function() {
								if(!confirm('".Yii::t('app','message.confirmPayment')."\\n"
									.Yii::t('app','model.ClientInvoice.Id').": '+$(this).parent().parent().children(':nth-child(1)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.Client').": '+$(this).parent().parent().children(':nth-child(2)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.Date').": '+$(this).parent().parent().children(':nth-child(3)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.Status').": '+$(this).parent().parent().children(':nth-child(4)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.PaymentDate').": '+$(this).parent().parent().children(':nth-child(5)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.Notes').": '+$(this).parent().parent().children(':nth-child(6)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.totalValue').": '+$(this).parent().parent().children(':nth-child(7)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.totalDeposits').": '+$(this).parent().parent().children(':nth-child(8)').text()
									+'\\n".Yii::t('app','model.ClientInvoice.totalPending').": '+$(this).parent().parent().children(':nth-child(9)').text())) return false;
								var th=this;
								var afterDelete=function(){};
								$.fn.yiiGridView.update('{client-invoice-grid}', {
									type:'POST',
									url:$(this).attr('href'),
									success:function(data) {
										$.fn.yiiGridView.update('{client-invoice-grid}');
										afterDelete(th,true,data);
									},
									error:function(XHR) {
										return afterDelete(th,false,XHR);
									}
								});
								return false;
							}",
     				),
				'deposits'=>array(
					'url'=>'$this->grid->controller->createUrl("/ClientInvoiceDeposit/create",array("headerId"=>$data->id))',
					'visible'=>'(Yii::app()->user->checkAccess("ClientInvoiceDeposit.Create"))?true:false;',
					'imageUrl'=>Yii::app()->params['General_BaseImageDirectory'].'PartialPayment.png',
					'label'=>Yii::t('app','general.label.Deposits'),
				),
				'update'=>array(
					'url'=>'$this->grid->controller->createUrl("/ClientInvoiceDetail/create",array("headerId"=>$data->id))',
					'visible'=>'(Yii::app()->user->checkAccess("ClientInvoiceDetail.Create"))?true:false;',
					'label'=>Yii::t('app','general.label.ClientInvoiceDetail'),
				),
			),
		),
	),
)); ?>
