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
/* @var $this ProductController */
/* @var $model Product */
/* @var $form TbActiveForm */
?>
<legend><?php echo Yii::t('app','general.label.Product');?></legend>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'product-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->textFieldRow($model,'code',array('class'=>'span1', 'size'=>5,'maxlength'=>5,)); ?>
<?php echo $form->textFieldRow($model,'name',array('class'=>'span3', 'size'=>45,'maxlength'=>45,)); ?>
<?php Utilities::select2Row($this,'Product',$form,$model,'measure_unit',MeasureUnit::getList(),'MeasureUnit',Yii::t('app','general.label.MeasureUnit'),'180px') ?>
<?php echo $form->textFieldRow($model,'custom_order', array('class'=>'span1')); ?>
<?php Utilities::formToolTip($this, 'custom_order'); ?>
<?php echo $form->textFieldRow($model,'default_qty',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->default_qty<=0 ? '': $model->default_qty,)); ?>
<?php Utilities::formToolTip($this, 'default_qty'); ?>
<?php echo $form->textFieldRow($model,'default_value',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->default_value<=0 ? '': $model->default_value,)); ?>
<?php Utilities::formToolTip($this, 'default_value'); ?>
<?php Utilities::toggleButtonRow($form,$model,'stock_movement'); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('Product_code'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->

<?php Utilities::buildNewRecordDialog($this); ?>