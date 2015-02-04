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
/* @var $form TbActiveForm */
?>
<legend><?php echo Yii::t('app','general.label.StockMovements');?></legend>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'stock-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->textFieldRow($model,'quantity',array('class'=>'span2', 'size'=>8,'maxlength'=>8,'value'=>$model->quantity<=0 ? '': $model->quantity,)); ?>
<?php Utilities::select2Row($this,'Stock',$form,$model,'product',Product::getList(),'Product',Yii::t('app','general.label.Product'),'180px') ?>
<?php echo $form->textFieldRow($model,'detail_unit_value',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->detail_unit_value<=0 ? '': $model->detail_unit_value,)); ?>
<?php echo $form->textFieldRow($model,'manual_unit_value',array('class'=>'span2', 'size'=>11,'maxlength'=>11,'value'=>$model->manual_unit_value<=0 ? '': $model->manual_unit_value,)); ?>
<?php Utilities::formDatePicker($form, $model,'date') ?>
<?php Utilities::toggleButtonRow($form,$model,'movement_type','movement_type',Yii::t('app','general.label.stockIn'),Yii::t('app','general.label.stockOut')); ?>
<?php echo $form->textAreaRow($model,'notes', array('class'=>'span5', 'rows'=>1, 'size'=>100,'maxlength'=>100)); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('Stock_quantity'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->

<?php Utilities::buildNewRecordDialog($this); ?>