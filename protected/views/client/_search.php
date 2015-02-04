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
/* @var $this ClientController */
/* @var $model Client */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Id')); ?>
		<?php echo $form->textFieldRow($model,'id', array('class'=>'span1')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Code')); ?>
		<?php echo $form->textFieldRow($model,'code',array('class'=>'span2', 'size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Name')); ?>
		<?php echo $form->textFieldRow($model,'name',array('class'=>'span3', 'size'=>60,'maxlength'=>75)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Description')); ?>
		<?php echo $form->textAreaRow($model,'description', array('class'=>'span4', 'rows'=>1, 'size'=>100,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Address')); ?>
		<?php echo $form->textAreaRow($model,'address', array('class'=>'span4', 'rows'=>1, 'size'=>100,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Phone')); ?>
		<?php echo $form->textFieldRow($model,'phone',array('class'=>'span2', 'size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,Yii::t('app','model.Client.Mobile')); ?>
		<?php echo $form->textFieldRow($model,'mobile',array('class'=>'span3', 'size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','general.label.Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->