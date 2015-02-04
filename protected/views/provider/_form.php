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
/* @var $this ProviderController */
/* @var $model Provider */
?>
<legend><?php echo Yii::t('app','general.label.Provider');?></legend>
<div class="form">
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'provider-form',
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'well'),
		'enableAjaxValidation'=>false,
));
echo $form->errorSummary($model);
?>

<?php echo $form->textFieldRow($model,'code',array('class'=>'span2', 'size'=>15,'maxlength'=>15,)); ?>
<?php echo $form->textFieldRow($model,'name',array('class'=>'span3', 'size'=>60,'maxlength'=>75,)); ?>
<?php echo $form->textAreaRow($model,'description', array('class'=>'span4', 'rows'=>1, 'size'=>100,'maxlength'=>100)); ?>
<?php echo $form->textAreaRow($model,'address', array('class'=>'span4', 'rows'=>1, 'size'=>100,'maxlength'=>100)); ?>
<?php echo $form->textFieldRow($model,'phone',array('class'=>'span2', 'size'=>20,'maxlength'=>20,)); ?>
<?php echo $form->textFieldRow($model,'mobile',array('class'=>'span3', 'size'=>50,'maxlength'=>50,)); ?>

<?php Utilities::actionSaveButton($this, $model->isNewRecord)?>

<?php $this->endWidget(); ?>

<?php Utilities::initialFocus('Provider_code'); ?>
<?php Utilities::getFlashes(); ?>

</div><!-- form -->

