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
/* @var $data Product */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.Id'))); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.Code'))); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.Name'))); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.MeasureUnit'))); ?>:</b>
	<?php echo CHtml::encode($data->measure_unit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.CustomOrder'))); ?>:</b>
	<?php echo CHtml::encode($data->custom_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.DefaultQty'))); ?>:</b>
	<?php echo CHtml::encode($data->default_qty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.DefaultValue'))); ?>:</b>
	<?php echo CHtml::encode($data->default_value); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel(Yii::t('app','model.Product.StockMovement'))); ?>:</b>
	<?php echo CHtml::encode($data->stock_movement); ?>
	<br />

	*/ ?>

</div>