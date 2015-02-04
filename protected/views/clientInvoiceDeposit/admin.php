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
$totalProvider=$model->totalProvider();
$reInstall=Utilities::registerDatePickerReInstall(array('date'));
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'=>'client-invoice-deposit-grid',
    'type'=>'striped bordered condensed',
	'dataProvider'=>$model->search(),
	'fixedHeader'=>true,'headerOffset'=>40,
	'afterAjaxUpdate'=>$reInstall,
	'columns'=>array(
		Utilities::editableColumnDatePicker($this,$model,'ClientInvoiceDeposit/updateDate','date'),
		array(
			'class'=>'EditableColumn',
			'name'=>'value',
			'type'=>'number',
			'footer'=>$model->totalProvider()->sumTotalValue,
			'htmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalColumn']),
			'footerHtmlOptions'=>array('style'=>Yii::app()->params['General_Styles_TotalFooter']),
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDeposit/updateValue'),
					'inputclass'=>'span2',
			),
		),
		array(
			'class'=>'EditableColumn',
			'name'=>'notes',
			
			'editable'=>array(
					'url'=>CController::createUrl('ClientInvoiceDeposit/updateNotes'),
					'inputclass'=>'span3',
					
					
			),
		),
		Utilities::toggleButtonColumn('final_payment','ClientInvoiceDeposit/toggle'),
		array (
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'header'=>Yii::t('app','general.label.ops'),
			'deleteConfirmation'=>"js:'".Yii::t('app','message.confirmDelete')."\\n"
				.Yii::t('app','model.ClientInvoiceDeposit.Date').": '+$(this).parent().parent().children(':nth-child(1)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDeposit.Value').": '+$(this).parent().parent().children(':nth-child(2)').text()
				+'\\n".Yii::t('app','model.ClientInvoiceDeposit.Notes').": '+$(this).parent().parent().children(':nth-child(3)').text()",
		),
	),
)); ?>