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
/* @var $this ProviderInvoiceDepositController */
/* @var $model ProviderInvoiceDeposit */
?>
<legend><?php echo Yii::t('app','general.label.ProviderInvoiceDeposit');?></legend>
<?php $this->renderPartial('/providerInvoice/_view', array('model'=>$model->providerInvoice)); ?>
<?php if($model->providerInvoice->status==Yii::app()->params['ProviderInvoice_final_status']) return; ?>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'provider-invoice-deposit-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->hiddenField($model, 'provider_invoice'); ?>
<?php Utilities::formDatePicker($form, $model,'date') ?>
<?php echo $form->textFieldRow($model,'value',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->value<=0 ? '': $model->value,)); ?>
<?php echo $form->textFieldRow($model,'notes',array('class'=>'span3', 'size'=>60,'maxlength'=>80,)); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('ProviderInvoiceDeposit_value'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->