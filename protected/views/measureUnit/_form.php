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
/* @var $this MeasureUnitController */
/* @var $model MeasureUnit */
/* @var $form TbActiveForm */
?>
<legend><?php echo Yii::t('app','general.label.MeasureUnit');?></legend>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'measure-unit-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->textFieldRow($model,'code',array('class'=>'span1', 'size'=>5,'maxlength'=>5,)); ?>
<?php echo $form->textFieldRow($model,'name',array('class'=>'span3', 'size'=>45,'maxlength'=>45,)); ?>
<?php echo $form->textFieldRow($model,'eq_reference',array('class'=>'span2', 'size'=>6,'maxlength'=>6,'value'=>$model->eq_reference<=0 ? '': $model->eq_reference,)); ?>
<?php Utilities::formToolTip($form,'eq_reference'); ?>
<?php Utilities::toggleButtonRow($form,$model,'reference'); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('MeasureUnit_code'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->

