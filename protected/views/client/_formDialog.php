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
<div class="form" id="jobDialogForm">
 
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'job-form',
    'enableAjaxValidation'=>true,
)); 
//I have enableAjaxValidation set to true so i can validate on the fly the
?>
 
    <p class="note">Fields with <span class="required">*</span> are required.</p>
 
    <?php echo $form->errorSummary($model); ?>
 
    <div class="row">
        <?php echo $form->labelEx($model,'jid'); ?>
        <?php echo $form->textField($model,'jid',array('size'=>60,'maxlength'=>90)); ?>
        <?php echo $form->error($model,'jid'); ?>
    </div>
 
    <div class="row">
        <?php echo $form->labelEx($model,'jdescr'); ?>
        <?php echo $form->textField($model,'jdescr',array('size'=>60,'maxlength'=>180)); ?>
        <?php echo $form->error($model,'jdescr'); ?>
    </div>
 
    <div class="row buttons">
        <?php echo CHtml::ajaxSubmitButton(Yii::t('job','Create Job'),CHtml::normalizeUrl(array('job/addnew','render'=>false)),array('success'=>'js: function(data) {
                        $("#ClientInvoice_client").append(data);
                        $("#jobDialog").dialog("close");
                    }'),array('id'=>'closeJobDialog')); ?>
    </div>
 
<?php $this->endWidget(); ?>
 
</div>