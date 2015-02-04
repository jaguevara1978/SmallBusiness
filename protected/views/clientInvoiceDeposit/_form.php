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
?>
<legend><?php echo Yii::t('app','general.label.ClientInvoiceDeposit');?></legend>
<?php $this->renderPartial('/clientInvoice/_view', array('model'=>$model->clientInvoice)); ?>
<?php if($model->clientInvoice->status==Yii::app()->params['ClientInvoice_final_status']) return; ?>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'client-invoice-deposit-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->hiddenField($model, 'client_invoice'); ?>
<?php Utilities::formDatePicker($form, $model,'date','dateDeposit'); ?>
<?php echo $form->textFieldRow($model,'value',array('class'=>'span2', 'size'=>11,'maxlength'=>11)); ?>
<?php echo $form->textFieldRow($model,'notes',array('class'=>'span3', 'size'=>60,'maxlength'=>80)); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('ClientInvoiceDeposit_value'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->