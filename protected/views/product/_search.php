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
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.Id')) ?>
		<?php echo $form->textFieldRow($model,'id', array('class'=>'span1')); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.Code')) ?>
		<?php echo $form->textFieldRow($model,'code',array('class'=>'span1', 'size'=>5,'maxlength'=>5,)); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.Name')) ?>
		<?php echo $form->textFieldRow($model,'name',array('class'=>'span3', 'size'=>45,'maxlength'=>45,)); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.MeasureUnit')) ?>
		<?php Utilities::select2Row($this,'Product',$form,$model,'measure_unit',MeasureUnit::getList(),'MeasureUnit',Yii::t('app','general.label.MeasureUnit'),'180px') ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.CustomOrder')) ?>
		<?php echo $form->textFieldRow($model,'custom_order', array('class'=>'span1')); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.DefaultQty')) ?>
		<?php echo $form->textFieldRow($model,'default_qty',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->default_qty<=0 ? '': $model->default_qty,)); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.DefaultValue')) ?>
		<?php echo $form->textFieldRow($model,'default_value',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->default_value<=0 ? '': $model->default_value,)); ?>
	</div>

	<div class="row">
		<?php $form->label($model,Yii::t('app','model.Product.StockMovement')) ?>
		<?php Utilities::toggleButtonRow($form,$model,'stock_movement'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','general.label.Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->