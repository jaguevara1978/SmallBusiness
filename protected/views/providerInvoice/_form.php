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
/* @var $this ProviderInvoiceController */
/* @var $model ProviderInvoice */
?>
<legend><?php echo Yii::t('app','general.label.ProviderInvoice');?></legend>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'provider-invoice-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php Utilities::select2Row($this,'ProviderInvoice',$form,$model,'provider',Provider::getList(),'Provider',Yii::t('app','general.label.Provider'),'250px') ?>
<?php Utilities::formDatePicker($form, $model,'date') ?>
<?php Utilities::select2Row($this,'ProviderInvoice',$form,$model,'status',Status::getList('PRI'),'Status',Yii::t('app','general.label.Status'),'180px',false) ?>
<?php Utilities::formDatePicker($form, $model,'payment_date') ?>
<?php echo $form->textAreaRow($model,'notes', array('class'=>'span5', 'rows'=>1, 'size'=>200,'maxlength'=>200)); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('ProviderInvoice_provider'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->

<?php Utilities::buildNewRecordDialog($this); ?>